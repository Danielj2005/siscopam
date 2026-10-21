
<div class="h-16 w-full mb-16 text-center ">
    @isset($icon)
        <div class="{{ $iconClass }}  flex h-12 items-center justify-center p-4 rounded-full w-12">
            {{ $icon }}
        </div>
    @endisset
    
    @isset($title)
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $title }} <span class="mx-3 absolute rounded-full text-sm px-2 bg-amber-400 text-dark">Popular</span></h2>
        <span class="text-green-500 fw-semibold rounded-full text-md px-2"> <sup>{{  $currentCoin }}</sup> <b> {{ $price }} </b> <span> {{ $time}}</span> </span>
    @endisset
</div>