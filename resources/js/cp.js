import SubscriptionActions from "./components/SubscriptionActions.vue";

Statamic.booting(() => {
    Statamic.$components.register('subscription-actions', SubscriptionActions);
});
