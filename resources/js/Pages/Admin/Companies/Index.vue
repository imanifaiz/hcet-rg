<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { usePagination } from 'vue-request';
import axios from 'axios';
import {computed, ref, useTemplateRef} from "vue";
import { EditOutlined, DeleteOutlined } from '@ant-design/icons-vue';
import {router} from "@inertiajs/vue3";
import DeleteConfirmation from "@/Components/DeleteConfirmation.vue";
import UpdateForm from "./Partials/Form.vue";
import DataTable from "@/Components/DataTable.vue";

const datatable = useTemplateRef('datatable');

const columns = [
    {
        title: 'Index',
        key: 'index',
        width: 80,
    },
    {
        title: 'Name',
        dataIndex: 'name',
        sorter: true,
        width: '20%',
    },
    {
        title: 'Email',
        dataIndex: 'email',
        width: '30%',
    },
    {
        title: 'Logo',
        dataIndex: 'logo',
        width: '15%',
    },
    {
        title: 'Website',
        dataIndex: 'website',
    },
    {
        title: 'Action',
        key: 'action',
        width: 80,
    },
];

const refreshDataTable = () => {
    datatable.value?.run();
}

const deleteRecord = function (id) {
    try {
        return new Promise((resolve, reject) => {
            router.delete(route('admin.companies.destroy', id), {
                preserveState: false,
                onSuccess: () => {
                    resolve();
                },
                onError: (errors) => {
                    reject(errors);
                },
            });
        });
    } catch (e) {
        console.log(e);
    }
}

const openCompanyForm = ref(false);
const selectedCompany = ref();
const showCompanyModal = (company) => {
    selectedCompany.value = company ?? {
        name: '',
        email: '',
        website: '',
        logo: '',
    };
    openCompanyForm.value = true;
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="w-full flex justify-between align-center">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Manage Companies
                </h2>
                <a-button @click="showCompanyModal()">Add Company</a-button>
            </div>
        </template>

        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 py-12">
            <div class="overflow-hidden dark:bg-gray-800">
                <DataTable ref="datatable" :resource="route('admin.companies.data-table')" :columns="columns">
                    <template #bodyCell="{ index, column, text, record, pagination }">
                        <template v-if="column.key === 'index'">
                            {{ ((pagination.current ?? 1) - 1) * (pagination.pageSize ?? 1) + index + 1 }}
                        </template>

                        <template v-if="column.dataIndex === 'logo'">
                            <img :src="text" alt="logo" class="h-20">
                        </template>

                        <template v-if="column.dataIndex === 'website'">
                            <a :href="text" target="_blank" class="text-blue-500 font-semibold underline">{{ text }}</a>
                        </template>

                        <template v-if="column.key === 'action'">
                            <div class="flex justify-between items-baseline">
                                <a href="#">
                                    <edit-outlined @click="showCompanyModal(record)" class="text-blue-500 font-semibold"/>
                                </a>

                                <delete-confirmation :confirm="() => deleteRecord(record.id)">
                                    <delete-outlined class="text-red-500 font-semibold"/>
                                </delete-confirmation>
                            </div>
                        </template>
                    </template>
                </DataTable>
            </div>
        </div>

        <UpdateForm v-model="openCompanyForm" :company="selectedCompany" @record-saved="refreshDataTable" />
    </AuthenticatedLayout>
</template>

<style scoped>
:deep(.ant-input-search) {
    width: 300px !important;
}
:deep(.ant-input-group .ant-input) {
    padding-top: 0.3em !important;
    padding-bottom: 0.3em !important;
    border: none !important;
}
</style>
