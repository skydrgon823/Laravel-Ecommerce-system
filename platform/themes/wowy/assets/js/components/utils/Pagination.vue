<template>
    <renderless-pagination
        :data="data"
        @on-click-paging="onPaginationChangePage">
        <nav v-if="computed.hasPages"
            slot-scope="{ data, computed, pageButtonEvents }">
            <ul class="pagination">
                <li v-for="(element, index) in data.links"
                    :key="index + element.url"
                    class="page-item"
                    :class="{active: element.active, disabled: !element.url}"
                    :aria-disabled="element.active ? 'true' : null">
                    <a :href="element.url"
                        :rel="index == 0 ? 'previous' : (index == data.links.length - 1) ? 'next' : null"
                        :aria-label="element.label"
                        class="page-link"
                        v-on="pageButtonEvents({element, page: element.label})"
                        v-html="element.label"></a>
                </li>
            </ul>
        </nav>
    </renderless-pagination>
</template>

<script>
import RenderlessPagination from './RenderlessPagination.vue';

export default {
    props: {
        data: {
            type: Object,
            default: () => {}
        },
    },
    methods: {
        onPaginationChangePage (params) {
            this.$emit('on-click-paging', params);
        },
    },
    components: {
        RenderlessPagination
    }
}
</script>
