<script>
export default {
    props: {
        data: {
            type: Object,
            default: () => {}
        },
    },
    computed: {
        currentPage () {
            return this.data.current_page;
        },
        lastPage () {
            return this.data.last_page;
        },
        hasMorePages() {
            return this.currentPage && this.currentPage < this.lastPage;
        },
        hasPages() {
            return this.currentPage && (this.currentPage != 1 || this.hasMorePages);
        },
    },
    methods: {
        selectPage (params) {
            this.$emit('on-click-paging', params);
        }
    },
    render () {
        return this.$scopedSlots.default({
            data: this.data,
            computed: {
                currentPage: this.currentPage,
                hasMorePages: this.hasMorePages,
                lastPage: this.lastPage,
                hasPages: this.hasPages,
            },
            pageButtonEvents: (params) => ({
                click: (e) => {
                    e.preventDefault();
                    this.selectPage(params);
                }
            })
        });
    }
}
</script>
