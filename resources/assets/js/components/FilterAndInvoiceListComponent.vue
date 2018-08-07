<template>
    <div class="filter-list-wrapper">
        <div class="filter">
            <div class="filter-type-toggle">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-default"
                            :class="{'active':(searchType === 'basic-filter')}"
                            v-on:click="searchType = 'basic-filter'"
                    >Basic</button>
                    <button class="btn btn-default"
                            :class="{'active':(searchType === 'advance-filter')}"
                            v-on:click="searchType = 'advance-filter'"
                            disabled
                    >Advance</button>
                </div>
            </div>
            <div class="basic-filer" v-show="searchType === 'basic-filter'">
                <div class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control" placeholder="Type the text or keyword you want to search here" v-model="basicSearchStr">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" v-on:click="loadInvoices('/api/invoices/?page=1')">
                                SEARCH
                            </button>
                        </span>
                        <span class="input-group-btn">
                            <button class="btn btn-default" v-on:click="loadInvoices(null)">
                                RESET
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="advance-filter" v-show="searchType === 'advance-filter'"></div>
        </div>
        <div class="list">
            <div class="text-center" v-if="invoices.length < 1">
                No invoice has been found in this system.
            </div>
            <table class="table table-striped" v-if="invoices.length > 0">
                <thead>
                    <tr>
                        <th>Ref. #</th>
                        <th>Receiptant</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th>Issue Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="invoice" v-for="invoice in invoices"
                        :class="{'text-muted':(invoice.status === 'cancelled'), 'text-bold': (invoice.status === 'issued')}"
                    >
                        <td>{{ invoice.ref}}</td>
                        <td>{{ invoice.client_name }}</td>
                        <td class="text-uppercase">{{ invoice.status }}</td>
                        <td>{{ invoice.created_at | moment('Do, MMM YYYY') }}</td>
                        <td v-if="invoice.issued_at != null">{{ invoice.issued_at | moment('Do, MMM YYYY') }}</td>
                            <td v-if="invoice.issued_at == null">NOT ISSUED</td>
                        <td>
                            <div class="btn-sm-group">
                                <a class="btn btn-sm btn-default" :href="'/innov/invoices/'+invoice.id">View</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="page-nav" v-if="invoices.length > 0">
            <ul>
                <li
                    v-if="previousPageUrl != null"
                    v-on:click="loadInvoices(previousPageUrl)"
                ><i class="glyphicon glyphicon-chevron-left"></i></li>
                <li class="current"><span>{{ this.currentPage }}</span></li>
                <li
                    v-if="nextPageUrl != null"
                    v-on:click="loadInvoices(nextPageUrl)"
                ><i class="glyphicon glyphicon-chevron-right"></i></li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: "FilterAndInvoiceListComponent",
        created: function() {
           var currentInvoicesUri = '/api/invoices/?page=1';
            this.loadInvoices(currentInvoicesUri);
        },
        data:function() {
          return {
              currentPage: 1,
              previousPageUrl: null,
              nextPageUrl: null,
              invoices: [],
              searchType: 'basic-filter',
              basicSearchStr: '',
          };
        },
        methods: {
            loadInvoices: function (currentPageUri) {
                if (currentPageUri != null) {
                    currentPageUri += '&searchStr=' + this.basicSearchStr;
                } else {
                    currentPageUri = '/api/invoices/?page=1';
                    this.basicSearchStr = null;
                }
                axios.get(currentPageUri)
                    .then((response) => {
                        response = response.data;
                        if (response.success) {
                            this.invoices = response.invoices.data;
                            this.previousPageUrl = response.invoices.prev_page_url;
                            this.currentPage = response.invoices.current_page;
                            this.nextPageUrl = response.invoices.next_page_url;

                        } else {
                            console.error(response.error);
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            },
        }
    }
</script>

<style scoped>
    .text-bold {
        font-weight: 700;
    }
    .text-muted {
        color: #dcdcdc;
    }
    .filter-type-toggle {
        margin-bottom: 15px;
    }
    .list { margin-top: 25px;}
    .page-nav {margin-top: 15px;}
    .page-nav ul {margin: auto; width: 100px; list-style: none; padding-left: 0px;}
    .page-nav ul li {float: left; padding: 5px;}
    .page-nav ul li.current span {border-radius: 3px; border: 1px solid #ddd; display: block; padding: 0px 8px;}
    .basic-filer .input-group-sm input { width: 195px;}
</style>