<?php

use Botble\Menu\Models\MenuNode;

add_filter(BASE_FILTER_BEFORE_RENDER_FORM, function ($form, $data) {
    if (get_class($data) == MenuNode::class) {
        $iconImage = $data->icon_image ?: $data->getMetaData('icon_image', true);

        if ($form->getFormHelper()->hasCustomField('themeIcon')) {
            $form
                ->modify('icon_font', 'themeIcon', [
                    'attr'        => [
                        'placeholder' => null,
                    ],
                    'empty_value' => __('-- None --'),
                ]);
        }

        $form
            ->addAfter('icon_font', 'icon_image', 'mediaImage', [
                'label'      => __('Icon image'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'data-update' => 'icon_image',
                ],
                'value'      => $iconImage,
                'help_block' => [
                    'text' => __('It will replace Icon Font if it is present.'),
                ],
                'wrapper'    => [
                    'style' => 'display: block;',
                ],
            ]);
    }

    return $form;
}, 124, 3);

add_action([BASE_ACTION_AFTER_CREATE_CONTENT, BASE_ACTION_AFTER_UPDATE_CONTENT], function ($type, $request, $object) {
    if (get_class($object) == MenuNode::class) {
        if ($request->has('data.icon_image')) {
            if ($iconImage = $request->input('data.icon_image')) {
                MetaBox::saveMetaBoxData($object, 'icon_image', $iconImage);
            } else {
                MetaBox::deleteMetaData($object, 'icon_image');
            }

            return true;
        }

        $menuNodes = json_decode($request->input('menu_nodes'), true);

        foreach ($menuNodes as $node) {
            if ($node['menuItem']['id'] == $object->id && isset($node['menuItem']['icon_image'])) {
                if ($iconImage = $node['menuItem']['icon_image']) {
                    MetaBox::saveMetaBoxData($object, 'icon_image', $iconImage);
                } else {
                    MetaBox::deleteMetaData($object, 'icon_image');
                }

                break;
            }
        }
    }
}, 170, 3);

add_filter('menu_nodes_item_data', function ($data) {
    $data->icon_image = $data->getMetaData('icon_image', true);

    return $data;
}, 170);
