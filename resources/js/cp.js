import SubscriptionActions from "./components/SubscriptionActions";
import RolePlans from "./components/RolePlans";


Statamic.booting(() => {
    Statamic.$components.register('subscription-actions', SubscriptionActions);
    Statamic.$components.register('role-plans', RolePlans);
});