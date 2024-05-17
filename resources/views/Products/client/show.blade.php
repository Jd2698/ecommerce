<x-app>
    <shop-product :product="{{ $product }}" :session="{{ $session }}" user="{{ Auth::user() }}" />
</x-app>
