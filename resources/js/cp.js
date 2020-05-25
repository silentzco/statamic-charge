import SubscriptionActions from "./components/SubscriptionActions";


Statamic.booting(() => {
    Statamic.$components.register('subscription-actions', SubscriptionActions);
});