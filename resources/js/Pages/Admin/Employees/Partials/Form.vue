<script setup>
import {ref, watch} from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";

const model = defineModel();

const props = defineProps(['employee']);

const companiesOpts = usePage().props.companiesOpts;

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone_no: '',
    company_id: '',
    _method: 'post'
});

const emit = defineEmits(['record-saved']);

const url = ref('');
const title = ref('');

watch(
    () => props.employee,
    (newEmployee) => {
        if (newEmployee) {
            form.first_name = newEmployee.first_name;
            form.last_name = newEmployee.last_name ?? '';
            form.email = newEmployee.email ?? '';
            form.phone_no = newEmployee.phone_no ?? '';
            form.company_id = newEmployee.company_id;
            form._method = newEmployee.id ? 'put' : 'post';

            url.value = newEmployee.id ? route('admin.employees.update', props.employee.id) : route('admin.employees.store');
            title.value = newEmployee.id ? 'Edit: ' + props.employee.full_name : 'Add New Employee';
        }
    },
    { immediate: true }
);

const submit = function() {
    form.post(url.value, {
        preserveState: true,
        onSuccess: () => {
            emit('record-saved');
            model.value = false;
        }
    });
}
</script>

<template>
    <a-modal
        v-model:open="model"
        :wrap-style="{ overflow: 'hidden' }"
        @ok="() => submit()"
        @cancel="() => form.reset()"
    >
        <template #title>
            <div ref="modalTitleRef" style="width: 100%;">{{ title }}</div>
        </template>

        <div class="bg-white dark:bg-gray-800">
            <section>
                <form
                    @submit.prevent="submit"
                    class="mt-6 space-y-6"
                >
                    <div>
                        <InputLabel for="first_name" value="First Name" />

                        <TextInput
                            id="first_name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.first_name"
                            required
                            autofocus
                            autocomplete="first_name"
                        />

                        <InputError class="mt-2" :message="form.errors.first_name" />
                    </div>

                    <div>
                        <InputLabel for="last_name" value="Last Name" />

                        <TextInput
                            id="last_name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.last_name"
                            required
                            autocomplete="last_name"
                        />

                        <InputError class="mt-2" :message="form.errors.last_name" />
                    </div>

                    <div>
                        <InputLabel for="email" value="Email" />

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            autocomplete="email"
                        />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel for="phone_no" value="Phone No." />

                        <TextInput
                            id="phone_no"
                            class="mt-1 block w-full"
                            v-model="form.phone_no"
                            autocomplete="phone_no"
                        />

                        <InputError class="mt-2" :message="form.errors.phone_no" />
                    </div>

                    <div>
                        <InputLabel for="company_id" value="Company" />

                        <Select
                            id="company_id"
                            class="mt-1 block w-full"
                            :options="companiesOpts"
                            v-model="form.company_id"
                            autocomplete="company_id"
                        />

                        <InputError class="mt-2" :message="form.errors.company_id" />
                    </div>
                </form>
            </section>
        </div>
    </a-modal>
</template>
