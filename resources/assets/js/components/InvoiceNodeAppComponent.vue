<template>
    <div class="app-wrapper">
        <div class="control-panel">
            <button
                    class="btn btn-primary create-invoice"
                    data-toggle="modal"
                    data-target="#create-invoice-form"
            >
                Create New Invoice
            </button>
        </div>
        <filter-and-list></filter-and-list>
        <create-new-invoice></create-new-invoice>
    </div>
</template>

<script>
    import FilterAndInvoiceListComponent from './FilterAndInvoiceListComponent.vue';
    import CreateNewInvoiceComponent from './CreateNewInvoiceComponent.vue';

    export default {
        name: "InvoiceNodeAppComponent",
        components: {
            'filter-and-list': FilterAndInvoiceListComponent,
            'create-new-invoice': CreateNewInvoiceComponent,
        },
    }
</script>

<style scoped>
    .app-wrapper .control-panel {margin-bottom: 15px; overflow: hidden;}
    .app-wrapper .control-panel .create-invoice {float: right;}
</style>