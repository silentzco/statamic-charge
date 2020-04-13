<template>
    <div>
        <dropdown-list>
            <dropdown-item
                class="warning"
                text="Cancel"
                 @click="canceling = true"
            >
                <confirmation-modal
                    v-if="canceling"
                    title="Cancel Subscription"
                    bodyText="Are you sure you want to cancel this subscription?"
                    :buttonText="__('Cancel Subscription')"
                    :danger="true"
                    @confirm="confirmed"
                    @cancel="canceling = false"
                >
                </confirmation-modal>
            </dropdown-item>
        </dropdown-list>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "SubscriptionActions",

        props: ["route"],

        data() {
            return {
                canceling: false
            };
        },

        methods: {
            confirmed() {
                this.$axios
                    .delete(this.route)
                    .then(response => {
                        location.reload();
                    })
                    .catch(() => {
                        this.$toast.error(__("Something went wrong"));
                    });
            }
        }
    };
</script>