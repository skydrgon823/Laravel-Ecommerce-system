import DiscountComponent from './components/DiscountComponent'
import Vue from 'vue';

Vue.component('discount-component', DiscountComponent);

/**
 * This let us access the `__` method for localization in VueJS templates
 * ({{ __('key') }})
 */
Vue.prototype.__ = key => {
    return _.get(window.trans, key, key);
};

new Vue({
    el: '#main-discount',
});
