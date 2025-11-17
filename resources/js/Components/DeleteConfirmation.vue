<script setup>
import { ExclamationCircleOutlined } from '@ant-design/icons-vue';
import { createVNode } from 'vue';
import {message, Modal} from 'ant-design-vue';

const props = defineProps([
    'confirm'
]);

const confirm = () => {
    if (props.confirm) {
        props.confirm();
        return;
    }
    message.success('Click on Yes');
};

const showConfirm = () => {
    Modal.confirm({
        title: 'Do you want to delete these items?',
        icon: createVNode(ExclamationCircleOutlined),
        okText: 'Delete',
        okType: "danger",
        onOk: () => confirm(),
        onCancel() {},
    });
};
</script>

<template>
    <a @click="showConfirm">
        <slot>Confirm</slot>
    </a>
</template>

