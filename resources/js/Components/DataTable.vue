<script setup>
import {computed, ref} from "vue";
import axios from "axios";
import {usePagination} from "vue-request";

const props = defineProps({
    columns: {
        type: Array,
        default: []
    },
    resource: {
        type: String
    }
});

const globalSearch = ref('');

const queryData = async (params) => {
    const response = await axios.get(props.resource, { params })

    return response.data;
};

const pagination = computed(() => ({
    total: total.value,
    current: current.value,
    pageSize: pageSize.value,
}));

const {
    data: dataSource,
    run,
    loading,
    current,
    pageSize,
    total,
} = usePagination(queryData, {
    pagination: {
        currentKey: 'page',
        pageSizeKey: 'pageSize',
    },
});

const handleTableChange = (
    pagination,
    filters,
    sorter,
) => {
    run({
        pageSize: pagination.pageSize,
        page: pagination.current,
        sortField: sorter.field,
        sortOrder: sorter.order,
        globalSearch: globalSearch.value,
        ...filters,
    });
};

defineExpose({
    run,
});

const onSearch = () => {
    run({
        pageSize: pagination.value.pageSize,
        page: 1,
        globalSearch: globalSearch.value,
    });
}

const resetGlobalSearch = () => {
    globalSearch.value = '';
    onSearch();
}
</script>

<template>
    <div class="datatable-wrapper">
        <div class="flex items-center mb-4">
            <a-input-search
                v-model:value="globalSearch"
                placeholder="Search..."
                @search="onSearch"
                :loading="loading"
                enter-button
            />

            <a-button danger v-if="globalSearch != ''" @click="resetGlobalSearch" class="ml-2">Reset</a-button>
        </div>

        <a-table
            ref="table"
            :columns="columns"
            :row-key="data => data.id"
            :data-source="dataSource?.data"
            :pagination="pagination"
            :loading="loading"
            @change="handleTableChange"
            @record-saved="handleTableChange"
        >
            <template #bodyCell="{ column, text, index, record }">
                <slot
                    name="bodyCell"
                    :index="index"
                    :column="column"
                    :text="text"
                    :record="record"
                    :pagination="pagination"
                />
            </template>
        </a-table>
    </div>
</template>
