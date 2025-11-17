<script setup>
defineProps(['company']);

const model = defineModel();
</script>

<template>
    <a-modal
         v-model:open="model"
         :wrap-style="{ overflow: 'hidden' }"
         :cancel-button-props="{ style: { display: 'none' } }"
         @ok="model = false"
    >
        <template #title>
            <div ref="modalTitleRef" style="width: 100%;">Company: {{ company.name }}</div>
        </template>

        <dl class="grid grid-cols-4 gap-4 mt-4">
            <dt class="col-span-1">Email: </dt>
            <dd class="col-span-3 font-semibold text-gray-700">{{ company.email }}</dd>

            <dt class="col-span-1">Website: </dt>
            <dd class="col-span-3 font-semibold">
                <a class="underline text-blue-500" target="_blank" :href="company.website">{{ company.website }}</a>
            </dd>

            <dt class="col-span-1">Logo: </dt>
            <dd class="col-span-3 font-semibold text-gray-700">
                <img :src="company.logo" :alt="company.name" class="w-60">
            </dd>
        </dl>
    </a-modal>
</template>
