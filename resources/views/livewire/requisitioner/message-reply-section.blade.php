
<div class="w-1/3 bg-white border border-gray-300 ml-4 shadow-md rounded-lg overflow-hidden max-h-[90vh] flex flex-col">

    <h3 class="text-lg font-bold mb-2 flex items-center bg-gray-800 text-white p-4">
        <div class="flex items-center justify-center w-12 h-12 bg-primary-200 rounded-full mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6 text-primary-500">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
            </svg>
        </div>
        Message & Reply Section
    </h3>


    <div class="p-3 h-full overflow-y-auto flex-grow">
        <div class="text-xs text-gray-700 message-container">
            @foreach ($messages as $message)
                <div class="flex items-start mb-4">
                    <div class="flex-shrink-0 mr-2">
                        <div class="flex items-center justify-center w-8 h-8 bg-gray-200 rounded-full">
                            <span class="text-sm font-bold text-primary-800">{{ $message->user->name[0] }}</span>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <div class="bg-gray-100 p-3 rounded-lg {{ auth()->id() === $message->user_id ? 'bg-primary-100' : '' }}">
                            <p class="font-bold">{{ $message->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $message->created_at->format('F d, Y h:i A') }}</p>
                            <p class="mt-1 text-sm text-gray-700">{{ $message->content }}</p>
                        </div>
                        <div class="mt-1">
                            <button wire:click="$set('replyingTo', {{ $message->id }})" class="px-2 py-1 text-primary-500 rounded text-xs font-bold">Reply</button>
                            @if (Auth::user()->id === $message->user_id)
                            <button wire:click="confirmDelete({{ $message->id }})" class="px-2 py-1 text-red-500 rounded text-xs font-bold">Delete</button>

                            @endif
                        </div>
                        @if ($replyingTo === $message->id)
                            <div class="mt-2 ml-4">
                                <textarea wire:model="replyContent" class="w-full p-2 border rounded-lg focus:ring focus:ring-primary-200 transition duration-200 ease-in-out" placeholder="Type your reply..."></textarea>
                                @error('replyContent')
                                    <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                                @enderror

                                <button wire:click="addReply({{ $message->id }})" wire:loading.attr="disabled" class="mt-2 px-4 py-2 bg-primary-500 text-white rounded hover:bg-primary-600 transition duration-200 ease-in-out" :disabled="!replyContent">
                                    <span>Send Reply</span>
                                </button>

                                <div wire:loading class="text-gray-500">Sending reply...</div>
                            </div>
                        @endif
                        @foreach ($message->replies as $reply)
                            <div class="ml-8 mt-2 border-l-2 border-gray-300 pl-3 {{ auth()->id() === $reply->user_id ? 'bg-primary-100' : 'bg-gray-50' }} rounded-lg relative">
                                <div class="flex items-center mb-2">
                                    <div class="flex items-center justify-center w-6 h-6 bg-gray-200 rounded-full mr-2">
                                        <span class="text-sm font-bold text-primary-800">{{ $reply->user->name[0] }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold">{{ $reply->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $reply->created_at->format('F d, Y h:i A') }}</p>
                                    </div>
                                       @if (Auth::user()->id === $reply->user_id)
                                       <button wire:click="confirmDelete({{ $reply->id }})" class="absolute top-0 right-0 mt-1 mr-1 text-red-500">
                                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                               <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                           </svg>
                                       </button>
                                @endif
                                </div>
                                <p class="mb-2 text-sm text-gray-700">{{ $reply->content }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="mt-2 p-3">
        <textarea wire:model="messageContent" class="w-full p-2 border rounded-lg focus:ring focus:ring-primary-200 transition duration-200 ease-in-out" placeholder="Type your message..."></textarea>
        @error('messageContent')
            <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
        @enderror
        <button wire:click="addMessage" wire:loading.attr="disabled" class="mt-2 px-4 py-2 bg-primary-500 text-white rounded hover:bg-primary-600 transition duration-200 ease-in-out" :disabled="!messageContent">
            <span>Send</span>
        </button>
        <div wire:loading.class="hidden" wire:target="addMessage" class="text-gray-500">Sending...</div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const disbursementVoucherId = @json($disbursement_voucher->id);
            console.log(`Subscribing to messages.${disbursementVoucherId}`);
            console.log("Echo is available");
            window.Echo.channel(`messages.${disbursementVoucherId}`)
                .listen('MessageSent', (message) => {
                    console.log("New Message:", message);
                    Livewire.emit('refreshMessages'); // Ensure this line is present
                })
                .listen('ReplyAdded', (reply) => {
                    console.log("New Reply:", reply);
                    Livewire.emit('refreshMessages'); // Ensure this line is present
                })
                .listen('MessageDeleted', (message) => {
                    console.log("Message Deleted:", message);
                    Livewire.emit('refreshMessages'); // Ensure this line is present
                });

        });
    </script>
</div>
