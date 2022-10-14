import CreateOrder from './components/CreateOrderComponent'
import {BModal, VBModal} from 'bootstrap-vue';
import Vue from 'vue';

Vue.component('b-modal', BModal);
Vue.directive('b-modal', VBModal);
Vue.component('create-order', CreateOrder);

/**
 * This let us access the `__` method for localization in VueJS templates
 * ({{ __('key') }})
 */
Vue.prototype.__ = key => {
    return _.get(window.trans, key, key);
};

Vue.filter('formatPrice', function (value) {
    return parseFloat(value).toFixed(2);
})

new Vue({
    el: '#main-order',
});
