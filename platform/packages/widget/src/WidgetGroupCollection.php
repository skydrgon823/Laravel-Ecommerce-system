<?php

namespace Botble\Widget;

use Botble\Widget\Contracts\ApplicationWrapperContract;
use Botble\Widget\Repositories\Interfaces\WidgetInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Language;
use Theme;

class WidgetGroupCollection
{
    /**
     * The array of widget groups.
     *
     * @var array
     */
    protected $groups;

    /**
     * @var ApplicationWrapperContract
     */
    protected $app;

    /**
     * @var Collection
     */
    protected $data = [];

    /**
     * Whether the settings data are loaded.
     *
     * @var boolean
     */
    protected $loaded = false;

    /**
     * Constructor.
     *
     * @param ApplicationWrapperContract $app
     */
    public function __construct(ApplicationWrapperContract $app)
    {
        $this->app = $app;
    }

    /**
     * Get the widget group object.
     *
     * @param string $sidebarId
     * @return WidgetGroup
     */
    public function group(string $sidebarId): WidgetGroup
    {
        if (isset($this->groups[$sidebarId])) {
            return $this->groups[$sidebarId];
        }
        $this->groups[$sidebarId] = new WidgetGroup(['id' => $sidebarId, 'name' => $sidebarId], $this->app);

        return $this->groups[$sidebarId];
    }

    /**
     * @param array $args
     * @return $this
     */
    public function setGroup(array $args): WidgetGroupCollection
    {
        if (isset($this->groups[$args['id']])) {
            $group = $this->groups[$args['id']];
            $group->setName(Arr::get($args, 'name'));
            $group->setDescription(Arr::get($args, 'description'));
            $this->groups[$args['id']] = $group;
        } else {
            $this->groups[$args['id']] = new WidgetGroup($args, $this->app);
        }

        return $this;
    }

    /**
     * @param string $groupId
     * @return $this
     */
    public function removeGroup(string $groupId): WidgetGroupCollection
    {
        if (isset($this->groups[$groupId])) {
            unset($this->groups[$groupId]);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @param string $sidebarId
     * @return string
     */
    public function render(string $sidebarId): string
    {
        $this->load();

        foreach ($this->data as $widget) {
            $this->group($widget->sidebar_id)
                ->position($widget->position)
                ->addWidget($widget->widget_id, $widget->data);
        }

        return $this->group($sidebarId)->display();
    }

    /**
     * Make sure data is loaded.
     *
     * @param boolean $force Force a reload of data. Default false.
     */
    public function load(bool $force = false)
    {
        if (!$this->loaded || $force) {
            $this->data = $this->read();
            $this->loaded = true;
        }
    }

    /**
     * @return Collection
     */
    protected function read(): Collection
    {
        $languageCode = null;
        if (is_plugin_active('language')) {
            $currentLocale = is_in_admin() ? Language::getCurrentAdminLocaleCode() : Language::getCurrentLocaleCode();
            $languageCode = $currentLocale && $currentLocale != Language::getDefaultLocaleCode() ? '-' . $currentLocale : null;
        }

        return app(WidgetInterface::class)->allBy(['theme' => Theme::getThemeName() . $languageCode]);
    }

    /**
     * @return Collection
     */
    public function getData(): Collection
    {
        return $this->data;
    }
}
