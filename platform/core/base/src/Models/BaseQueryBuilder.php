<?php

namespace Botble\Base\Models;

use Illuminate\Database\Eloquent\Builder;

class BaseQueryBuilder extends Builder
{
    /**
     * @param string $column
     * @param string|null $term
     * @param bool $isPartial
     * @return BaseQueryBuilder
     */
    public function addSearch(string $column, ?string $term, bool $isPartial = true): BaseQueryBuilder
    {
        if (!$isPartial) {
            $this->orWhere($column, 'LIKE', '%' . trim($term) . '%');

            return $this;
        }

        $searchTerms = explode(' ', $term);

        $sql = 'LOWER(' . $this->getGrammar()->wrap($column) . ') LIKE ? ESCAPE ?';

        foreach ($searchTerms as $searchTerm) {
            $searchTerm = mb_strtolower($searchTerm, 'UTF8');
            $searchTerm = str_replace('\\', $this->getBackslashByPdo(), $searchTerm);
            $searchTerm = addcslashes($searchTerm, '%_');

            $this->orWhereRaw($sql, ['%' . $searchTerm . '%', '\\']);
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getBackslashByPdo(): string
    {
        if (config('database.default') === 'sqlite') {
            return '\\\\';
        }

        return '\\\\\\';
    }
}
