<template>
    <div>
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col">Plan</th>
                    <th scope="col">Role</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(rolePlan, index) in mutableRolePlans">
                    <td>
                        <select :name="`subscription[roles][${index}][plan]`">
                            <option v-for="plan in plans" :key="plan.id" :value="plan.id" :selected="plan.id == rolePlan.plan">{{ plan.nickname }}</option>
                        </select>
                    </td>
                    <td>
                        <select :name="`subscription[roles][${index }][role]`">
                            <option v-for="role in roles" :key="role.id" :value="role.id" :selected="role.id == rolePlan.role">{{ role.title }}</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <button class="btn-primary" @click.prevent="addRow">Add</button>
    </div>
</template>

<script>
    export default {
        name: "RolePlans",

        props: ["plans", "roles", "rolePlans"],

        data() {
            return {
                mutableRolePlans: this.rolePlans ? this.rolePlans.slice() : []
            };
        },

        methods: {
            addRow() {
                this.$set(this.mutableRolePlans, this.mutableRolePlans.length, {
                    plan: "",
                    role: ""
                });
            }
        }
    };
</script>