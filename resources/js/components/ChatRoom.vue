<template>
     <div class="container">
        <div class="Box col-md-4 offset-4">
            <ul class="list-group mt-5" v-chat-scroll>
                <li class="list-group-item active">Chat Room <span class="badge rounded-pill bg-danger">{{ numberOfUsers }}</span><a id="xbtn" href='' class="btn btn-danger btn-sm" @click.prevent='deleteSession'>Delete Chats</a></li>
                <message
                v-for= '(value,index) in chat.message'
                :key= "index"
                :color= 'chat.color[index]' 
                :user= 'chat.user[index]'
                :time= 'chat.time[index]' 
                :message = 'chat.message[index]'/>
                <small class="badge rounded-pill bg-primary" v-if="typing.length != 0">{{ typing }}</small>
            </ul>
            <input type="text" @keyup.enter="send" v-model="message" class="form-control" placeholder="Type your message">
        </div>
            
     </div>
</template>

<script>
import Message from './Message'
    export default {
    components: { Message },
    data() {
        return {
            message:'',
            chat: {
                message:[],
                user:[],
                color:[],
                time:[]
            },
            typing:'',
            numberOfUsers:0
        }
    },
    methods: {
        send(){
    		if (this.message.length != 0) {
    			this.chat.message.push(this.message);
    			this.chat.color.push('success');
    			this.chat.user.push('you');
    			this.chat.time.push(this.getTime());
    			axios.post('/send', {
    				message : this.message,
                    chat:this.chat
    			  })
    			  .then(response => {
    			    this.message = ''
    			  })
    			  .catch(error => {
    			    console.log(error);
    			  });
    		}
    	},

        getTime(){
            let time = new Date();
            return time.getHours() + ':' + time.getMinutes()
        },
        oldMessages(){
            axios.post('/getold')
                  .then(response => {
                    if (response.data != '') {
                        this.chat = response.data;
                    }
                  })
                  .catch(error => {
                    console.log(error);
                  });
        },
        deleteSession(){
            axios.post('/delete')
            .then(response=> this.$toaster.success('Chat history is deleted') );
        }
    },
    mounted () {

        this.oldMessages();

        Echo.private('chat-channel')
            .listen('ChatEvent', (e) => {
                this.chat.message.push(e.message);
                this.chat.user.push(e.user);
                this.chat.color.push('warning');
                this.chat.time.push(this.getTime());
                axios.post('/save',{
                    chat : this.chat
                })
                .then(response => {
                })
                .catch(error => {
                console.log(error);
                });
                
            })
            .listenForWhisper('typing', (e) => {
                if (e.name.length != 0) {
                    this.typing = 'typing...';
                }else{
                    this.typing = '';
                }
            });

            /* For Notification */ 
            
        Echo.join('chat-channel')
            .here((users) => {
                this.numberOfUsers = users.length
            })
            .joining((user) => {
                this.numberOfUsers += 1;
                this.$toaster.success(user.name +' is here')
            })
            .leaving((user) => {
                this.numberOfUsers -= 1;
                this.$toaster.error(user.name +' left the chat')
            })
            .error((error) => {
                console.error(error);
            });
    },
    watch:{
        message(){
            Echo.private('chat-channel')
                .whisper('typing', {
                    name: this.message
                });
        }
    }       
}
</script>

<style>
.Box{
    outline: 1px solid #dbd3d3;
    border-radius: 5px;
    box-shadow: 10px 8px 10px #797474;
    background-color: #eee;
}
.Box #xbtn{float: right;font-size: 10px;color: white;}
.Box small{width:20%;}
.Box input{background-color: #eee}
.Box input:focus{background-color: #eee}
.list-group{ scrollbar-width: none;overflow-y: scroll;height:300px;}
</style>