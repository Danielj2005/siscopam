

<div {{ $attributes }} >
        
    <div class="h-16 w-full mb-16 text-center ">
        @isset($icon)
            <div class="{{ $iconClass }}  flex h-12 items-center justify-center p-4 rounded-full w-12">
                {{ $icon }}
            </div>
        @endisset
        
        @isset($title)
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $title }} 
            @isset($popular)
                <span class="mx-3 absolute rounded-full text-sm px-2 bg-amber-400 text-dark">Popular</span>
            @endisset

            </h2>
            <span class="text-green-500 fw-semibold rounded-full text-md px-2"> <sup>{{  $currentCoin }}</sup> <b> {{ $price }} </b> <span> {{ $time}}</span> </span>
        @endisset
    </div>
    
    
    @isset($subtitle)
        <div class="my-12"> <p class="text-slate-600 dark:text-slate-500">{{$subtitle}}</p> </div>
    @endisset
    
    <ul class="list-unstyled text-start">
        @foreach (explode(",",$list) as $item)
            <li class="flex gap-4 mb-2 dark:text-slate-400"><x-icon-check-circle class="text-emerald-500 w-4" />{{ $item}}</li>
        @endforeach
    </ul>

    <div class="w-full flex items-center justify-center">
        <a target="_blank" href="https://api.whatsapp.com/send?phone=584125238909" class="rounded-full bg-emerald-600 hover:bg-emerald-500 inline-flex items-center px-4 py-2 border border-transparent font-semibold text-xs text-white uppercase tracking-widest  active:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">¡Empezar a Crecer!</a>
    </div>

</div>