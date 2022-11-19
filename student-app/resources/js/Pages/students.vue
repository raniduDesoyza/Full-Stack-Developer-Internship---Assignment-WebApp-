<template>
    <app-layout>
        <!-- header -->
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Books List
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert" v-if="$page.props.flash.message">
                        <div class="flex">
                            <div>
                                <p class="text-sm">{{ $page.props.flash.message }}</p>
                            </div>
                        </div>
                    </div>
                    

                    <button 
                        @click="openForm()"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Add New Student</button>

                    <!-- table -->
                    <table class="table-fixed w-full posts-table">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 w-20">Student ID.</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Image</th>
                            <th class="px-4 py-2">Age</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                           <tr v-for="student in data.data">
                                <td class="px-4 py-2 border">{{ student.id }}</td>
                                <td class="px-4 py-2 border">{{ student.name }}</td>
                                <td class="px-4 py-2 border">
                                    <img v-if="student.image" :src="image_path(student.image)" />   
                                </td>
                                <td class="px-4 py-2 border">{{ student.age }}</td>
                                <td class="px-4 py-2 border">{{ student.status }}</td>
                                <td class="border px-4 py-2">
                                    <button style="margin: 5px 10px; "
                                        @click="openForm(student)"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</button>
                                    <button style="margin: 5px 5px;"
                                        @click="deleteStudent(student)"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <pagination :links="data.links"></pagination>
                    <student-form :isOpen="isFormOpen" :isEdit="isFormEdit" :form="formObject" @formsave="saveStudent" @formclose="closeModel"></student-form>
                        </div>
            </div>
        </div>
    </app-layout>
</template>

<script>

 const defaultFormObject = {
    name: null, image: null, age: null, status: null
 };

    import AppLayout from  "../Layouts/AppLayout.vue";
    import Pagination from "../Components/pagination.vue"
    import StudentForm from "../Components/Student/form.vue"

    export default{
        props: ['data'],
        components: {
            AppLayout,
            Pagination,
            StudentForm
        },
        data(){
            return{
                isFormOpen: false,
                isFormEdit: false,
                formObject: defaultFormObject
            }
        },
        methods: {

            image_path(image){
                return '/' + image;
            },
            saveStudent(student)
            {
                let url = '/students';
                if(student.id){
                    url = '/students/' + student.id;
                    student._method = 'PUT';
                }
                this.$inertia.post(url, student, {
                    onError: () => {

                    },
                    onSuccess: () => {
                        this.closeModel();
                    }
                });

            },
            closeModel()
            {
                this.isFormOpen = false;
            },
            openForm(student)
            {
                this.isFormOpen = true;
                this.isFormEdit = !!student;
                this.formObject = student ? Object.assign({}, student) : defaultFormObject;
                this.$page.props.errors = {};
            },
            deleteStudent(student)
            {
                if(window.confirm('are you sure?')){
                    this.$inertia.post('/students/' + student.id, {
                        _method: 'DELETE'
                    });
                }
            }
        }
    }
</script>