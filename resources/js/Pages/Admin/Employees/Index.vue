<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {ref, useTemplateRef} from "vue";
import {router} from "@inertiajs/vue3";
import UpdateForm from "./Partials/Form.vue";
import CompanyDetails from "@/Pages/Admin/Employees/Partials/CompanyDetails.vue";
import DataTable from "@/Components/DataTable.vue";
import DeleteConfirmation from "@/Components/DeleteConfirmation.vue";
import { EditOutlined, DeleteOutlined } from '@ant-design/icons-vue';

const datatable = useTemplateRef('datatable');
const columns = [
    {
        title: 'Index',
        key: 'index',
        width: 80,
    },
    {
        title: 'Full Name',
        dataIndex: 'full_name',
        sorter: true,
    },
    {
        title: 'Company',
        dataIndex: 'company_name',
    },
    {
        title: 'Email',
        dataIndex: 'email',
        width: '15%',
    },
    {
        title: 'Phone No.',
        dataIndex: 'phone_no',
        width: '15%',
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

const openCompanyModal = ref(false);
const selectedCompany = ref('');
const showCompanyModal = (company) => {
    openCompanyModal.value = true;
    selectedCompany.value = company;
};

const openEmployeeForm = ref(false);
const selectedEmployee = ref();
const showEmployeeModal = (employee) => {
    selectedEmployee.value = employee ?? {
        first_name: '',
        last_name: '',
        email: '',
        phone_no: '',
        company_id: '',
    };
    openEmployeeForm.value = true;
}

const deleteRecord = function (id) {
    try {
        return new Promise((resolve, reject) => {
            router.delete(route('admin.employees.destroy', id), {
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
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="w-full flex justify-between align-center">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Manage Employees
                </h2>
                <a-button @click="showEmployeeModal()">Add Employee</a-button>
            </div>
        </template>

        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 py-12">
            <div class="overflow-hidden dark:bg-gray-800">
                <DataTable ref="datatable" :resource="route('admin.employees.data-table')" :columns="columns">
                    <template #bodyCell="{ index, column, text, record, pagination }">
                        <template v-if="column.key === 'index'">
                            {{ ((pagination.current ?? 1) - 1) * (pagination.pageSize ?? 1) + index + 1 }}
                        </template>

                        <template v-if="column.dataIndex === 'company_name'">
                            <a @click="showCompanyModal(record.company)" class="text-blue-500 font-semibold underline">{{ record.company.name }}</a>
                        </template>

                        <template v-if="column.key === 'action'">
                            <div class="flex justify-between items-baseline">
                                <a href="#" @click="showEmployeeModal(record)">
                                    <edit-outlined class="text-blue-500 font-semibold"/>
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

        <CompanyDetails v-model="openCompanyModal" :company="selectedCompany" />

        <UpdateForm v-model="openEmployeeForm" :employee="selectedEmployee" @record-saved="refreshDataTable" />
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
