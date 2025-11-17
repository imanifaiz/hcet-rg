<script setup>
import {ref, watch} from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import {useForm} from "@inertiajs/vue3";

const model = defineModel();

const props = defineProps(['company']);

const form = useForm({
    name: '',
    email: '',
    website: '',
    logo: '',
    _method: 'post'
});

const url = ref('');
const title = ref('');
const logo = ref('');

const emit = defineEmits(['record-saved']);

watch(
    () => props.company,
    (newCompany) => {
        if (newCompany) {
            form.name = newCompany.name;
            form.email = newCompany.email ?? '';
            form.website = newCompany.website ?? '';
            form._method = newCompany.id ? 'put' : 'post';

            logo.value = newCompany.logo;
            url.value = newCompany.id ? route('admin.companies.update', props.company.id) : route('admin.companies.store');
            title.value = newCompany.id ? 'Edit: ' + props.company.name : 'Add New Company';
        }
    },
    { immediate: true }
);

const previewImage = function(e) {
    const file = e.target.files[0];
    form.logo = file;
    logo.value = URL.createObjectURL(file);
}

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
                    @submit.prevent="form.post(url)"
                    class="mt-6 space-y-6"
                >
                    <div>
                        <InputLabel for="name" value="Name" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />

                        <InputError class="mt-2" :message="form.errors.name" />
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
                        <InputLabel for="website" value="Website" />

                        <TextInput
                            id="website"
                            class="mt-1 block w-full"
                            v-model="form.website"
                            autocomplete="website"
                        />

                        <InputError class="mt-2" :message="form.errors.website" />
                    </div>

                    <div>
                        <InputLabel for="logo" value="Logo" />

                        <input
                            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600 mt-1 block w-full"
                            id="logo"
                            type="file"
                            @change="previewImage"
                        />

                        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                            {{ form.progress.percentage }}%
                        </progress>

                        <div v-if="logo" class="my-3">
                            <img :src="logo" class="w-48"/>
                        </div>

                        <InputError class="mt-2" :message="form.errors.logo" />
                    </div>
                </form>
            </section>
        </div>
    </a-modal>
</template>
