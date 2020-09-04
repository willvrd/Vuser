<template>
    <div class="container">

        <section v-if="loading"><loading></loading></section>

        <div v-if="success" class="row justify-content-center">

            <div class="col-md-9">

                <section>

                    <div class="card">
                        <div class="card-header text-uppercase font-weight-bold">{{title.plural}}</div>

                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Records per Page -->
                                    <div class="records-pages form-group row">
                                        <label for="selectRecordsPages" class="mx-2">{{trans.pagination.records}}:</label>
                                        <select name="selectRecordsPages" v-model="selectedRecords" @change="changeRecordsPerPage()">
                                            <option  v-for="(num,index) in recordsPerPage" :key="index"
                                                :value="num"
                                                >
                                                {{num}}
                                            </option>
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <!-- Search Table -->
                                    <form @submit.prevent="searchItem" id="search">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">{{trans.table.search}}</span>
                                            </div>
                                            <input type="text"
                                            class="form-control"
                                            :placeholder="trans.table.searchText"
                                            :aria-label="trans.table.searchText"
                                            aria-describedby="basic-addon1"
                                            name="query"
                                            v-model="searchQuery"
                                            :disabled="loading?true:false">
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <table v-if="data.length>0" class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th  v-for="(col, index) in columns" :key="index"
                                        scope="col"
                                        @click="changeOrderBy(col.name)"
                                        class="pointer"
                                        :class="{ active: params.filter.order.field == col.name }">
                                            {{col.title}}
                                            <span class="arrow" :class="sortOrders[col.name] > 0 ? 'asc' : 'dsc'">
                                            </span>
                                        </th>
                                        <th scope="col">{{trans.table.actions}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr  v-for="(item, index) in data" :key="index">

                                        <td v-for="(col,index2) in columns" :key="index2">
                                            {{item[col.name]}}
                                        </td>

                                        <td>
                                            <button type="button"
                                                class="btn btn-outline-primary"
                                                @click="editForm(item)">
                                            {{trans.btn.edit}}
                                            </button>
                                            <button type="button"
                                                class="btn btn-outline-danger"
                                                @click="deleteItem(item, index)">
                                            {{trans.btn.delete}}
                                            </button>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>

                            <!-- Errors Table -->
                            <alert :alert="alertTable"></alert>

                        </div>

                        <div class="card-footer">

                            <!-- Pagination -->
                            <div v-if="pagination.total > 1"  class="row">

                                <!-- Total Records -->
                                <div class="col-12 col-sm-4">
                                    {{trans.table.totalRecords}}: {{pagination.total}}
                                </div>

                                <!-- Pages -->
                                <div class="col-12 col-sm-4">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination d-flex justify-content-center">

                                                <!-- First -->
                                                <li class="page-item pointer" v-if="pagination.currentPage!=1">
                                                    <a class="page-link" @click="changePage('first')" title="First Page">
                                                        <span aria-hidden="true">&laquo;</span>
                                                        <span class="sr-only">{{trans.pagination.first}}</span>
                                                    </a>
                                                </li>

                                                <!-- Back -->
                                                <li class="page-item pointer" v-if="pagination.currentPage != 1">
                                                    <a class="page-link" @click="changePage('back')" title="Previous">
                                                        <span aria-hidden="true">{{trans.pagination.previous}}</span>
                                                        <span class="sr-only">{{trans.pagination.previous}}</span>
                                                    </a>
                                                </li>

                                                <!-- Next -->
                                                <li class="page-item pointer" v-if="pagination.currentPage < pagination.lastPage">
                                                    <a class="page-link" v-on:click="changePage('next')" title="Next">
                                                        <span aria-hidden="true">{{trans.pagination.next}}</span>
                                                        <span class="sr-only">{{trans.pagination.next}}</span>
                                                    </a>
                                                </li>

                                                <!-- Last -->
                                                <li class="page-item pointer" v-if="pagination.currentPage!=pagination.lastPage">
                                                    <a class="page-link" v-on:click="changePage('last')" title="Last Page">
                                                        <span aria-hidden="true">&raquo;</span>
                                                        <span class="sr-only">{{trans.pagination.last}}</span>
                                                    </a>
                                                </li>

                                            </ul>
                                        </nav>
                                </div>

                                <!-- Current Page -->
                                <div class="col-12 col-sm-4">
                                        <div class="pagination-pages form-group row d-flex justify-content-center">
                                        <label for="selectCurrentPage" class="mx-2">{{trans.pagination.current}}:</label>
                                        <select name="selectCurrentPage" v-model="selectedPage" @change="changePage('page')">
                                            <option  v-for="(num,index) in pagination.lastPage" :key="index"
                                                :value="num"
                                                >
                                                {{num}}
                                            </option>
                                        </select>
                                        </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </section>

            </div>

            <div class="col-md-3">

                <form-crud :path="path" :trans="trans" :idSelected="idSelected"
                    :modeUpdate="modeUpdate" @modeUpdate="modeUpdate = $event"
                    :item="item" @item="item = $event"
                    :loading="loading" @loading="loading = $event"
                    :hasChanged="hasChanged" @hasChanged="hasChanged = $event">
                </form-crud>

            </div>

        </div>

    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        title:{type: Object,required: true},
        path:{type: String,required: true}
    },
    mounted() {
        this.$nextTick(function () {
            this.init();
        })
    },
    data() {
        return {
            success: false,
            loading: true,
            errors: [],
            modeUpdate: false,
            data: [],
            alertTable:{
                status: false,
                type: 'alert-danger',
                text: 'Error - Sorry :('
            },
            item:[
                {
                    name:'name',
                    title:'Name',
                    required:true,
                    type:'text',
                    value:''
                },
                {
                    name:'guardName',
                    title:'Guard',
                    required:true,
                    type:'select',
                    options:[
                        {title: 'Web', value: 'web'},
                        {title: 'Api', value: 'api'}
                    ],
                    value: 'api'
                }
            ],
            params:{
                page: 1,
                filter:{
                    order:{
                        field:"id",
                        way:"desc"
                    }
                }
            },
            columns: [
                {title:"Id",name: "id"},
                {title:"Name",name: "name"},
                {title:"Guard",name: "guardName"},
                {title:"Created At",name: "createdAt"}
            ],
            sortOrders: {},
            pagination:{},
            selectedPage: 1,
            recordsPerPage:[12,25,50,100],
            selectedRecords: 12,
            trans:{
                table:{
                    actions: 'Actions',
                    search: 'Search',
                    searchText: 'Type here and press enter',
                    notResults: 'Not results available for now :)',
                    totalRecords: 'Total records'
                },
                btn:{
                    add: 'Add',
                    update: 'Update',
                    edit: 'Edit',
                    cancel: 'Cancel',
                    delete: 'Delete'
                },
                form:{
                    add:{
                        title: 'Add '+this.title.singular
                    },
                    edit:{
                        title: 'Update '+this.title.singular
                    },
                    selectOption: 'Select an option'
                },
                pagination:{
                    records: 'Records per Page',
                    current: 'Current Page',
                    first: 'First',
                    previous: 'Previous',
                    next: 'Next',
                    last: 'Last'
                },
                messages:{
                    itemAdded: 'Item Added :)',
                    itemUpdated: 'Item Updated :)',
                    error: 'Error :( ',
                },
                validations:{
                    required: 'is required'
                }
            },
            searchQuery: '',
            hasChanged: false,
            idSelected: null
        }
    },
    watch: {
        searchQuery: function(newValue, oldValue) {
            if(!newValue){
                this.getData()
            }
        },
        hasChanged:function(newValue){
            if(newValue){
                this.getData()
                this.hasChanged = false
            }
        }
    },
    methods:{

        init(){
            this.getData()
            this.setSortOrders()

            this.success = true
        },
        getData(){

            this.loading = true
            this.alertTable.status = false

            this.params.take = this.selectedRecords

            if(this.params.filter.search)
                this.params.filter.search = ""
            if(this.searchQuery)
                this.params.filter.search = this.searchQuery

            axios.get(this.path, {params:this.params})
            .then(response => {
                this.data = response.data.data;
                if(this.data.length>0){
                    this.pagination = response.data.meta.page
                    this.selectedPage = this.pagination.currentPage
                }else{
                    this.alertTable.status = true
                    this.alertTable.text = this.trans.table.notResults
                }

            })
            .catch(error => {
                console.log(error)
                this.alertTable.status = true
            })
            .finally(() => this.loading = false)

        },
        editForm(itemSelected){

            this.idSelected = itemSelected.id

            this.item.forEach(item => {
                item.value = itemSelected[item.name]
            });

            this.modeUpdate = true;
        },
        deleteItem(item,index){
            let resp = confirm(`Delete item:" ${item.name} " ?`);
            if(resp){
                this.loading = true
                axios.delete(this.path+`/${item.id}`)
                .then((response)=>{
                    this.data.splice(index, 1);
                    alert(response.data.data);
                }).catch(error=>{
                    alert(this.trans.messages.error)
                    console.log(error)
                })
                .finally(() => this.loading = false)
            }
        },
        setSortOrders(){
            var sortOrders = {}
            this.columns.forEach(function (key) {
                sortOrders[key.name] = 1
            })
            this.sortOrders = sortOrders
        },
        changeOrderBy(field){

            let tField = this.transformToSnakeCase(field)

            this.params.filter.order.field = tField

            if(this.params.filter.order.way=="asc")
                this.params.filter.order.way="desc"
            else
                this.params.filter.order.way="asc"

            this.sortOrders[field] = this.sortOrders[field] * -1

            this.getData()
        },
        changePage(type){

            if(type=="first"){
                this.params.page = 1;
            }else if(type=="last"){
                this.params.page = this.pagination.lastPage;
            }else if(type=="next"){
                this.params.page = this.pagination.currentPage+1;
            }else if(type=="back"){
                  if(this.pagination.currentPage>1)
                    this.params.page = this.pagination.currentPage-1;
                  else
                    return false;
            }else if(type=="page"){
                this.params.page = this.selectedPage;
            }

            this.getData();
        },
        changeRecordsPerPage(){

            this.params.take = this.selectedRecords
            this.params.page = 1
            this.getData();

        },
        transformToSnakeCase(string) {
            return string.replace(/[\w]([A-Z0-9])/g, function (m) {
                return m[0] + '_' + m[1]
            }).toLowerCase()
        },
        searchItem(){

            if(this.searchQuery)
               this.getData()

        }

    }
}
</script>

<style scoped>

    .pointer{
       cursor: pointer;
    }

    th {
        color: rgba(255,255,255,0.5) !important;
    }

    th.active {
        color: #fff !important;
    }

    th.active .arrow {
        opacity: 1;
    }

    .arrow {
        display: inline-block;
        vertical-align: middle;
        width: 0;
        height: 0;
        margin-left: 5px;
        opacity: 0.5;
    }

    .arrow.asc {
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-bottom: 4px solid #fff;
    }

    .arrow.dsc {
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 4px solid #fff;
    }

    .pagination .page-link{
        font-size: 1rem;
    }

</style>
