
<div class="w-1/2 mx-auto text-center">
    @if (session()->has('success') || session()->has('error'))
        <div x-data='{ show : true }' x-init="setTimeout(()=>show = false, 3000)" x-show="show"
            class="z-50 fixed top-36 right-2 py-2 px-4 rounded-xl bg-{{session('success') ? 'blue' : 'red'}}-500 text-white">
            <p>{{ session('success')}}</p>
            <p>{{ session('error')}}</p>
        </div>
    @endif
</div>
