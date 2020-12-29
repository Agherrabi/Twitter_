<template>
        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="tweetstore">
                    <textarea v-model="content" placeholder="write something here " class="rounded-lg border border-gray-200 w-full p-2 font-semibold resize-none focus:outline-none">

                    </textarea>
                    <span class="my-5 text-red-500" v-if="$page.errors.content">
                        {{$page.errors.content}}
                    </span>
                    <div class="flex items-center space-x-4 justify-end mt-3">
                        <p class="text-sm text-gray-400 font-thin" :class="{'text-red-500':remainingCharacter<0}" >{{remainingCharacter}} character remaining</p>
                        <button-vue :disabled="!canSubmit" class="bg-blue-500 hover:bg-blue-800 rounded-full font-extrabold">Tweet</button-vue>
                    </div>
                </form>
            </div>
        </div>
</template>

<script>

    import ButtonVue from '@/Jetstream/Button'
    export default {
        components:{
            ButtonVue,
        },

        data(){
            return{
                content: '',
            }
        },

        methods:{
            tweetstore(){
                    this.$inertia.post('tweets',{content:this.content},{preserveState:false})
            }
        },

        computed:{
                remainingCharacter (){
                    return 300 - this.content.length;
                },
                canSubmit (){
                    return this.content.length && this.remainingCharacter > 0
                }

        }


    }
</script>
<style scoped>
    button:disabled{
        opacity :50%;
        cursor: not-allowed;
    }
</style>
