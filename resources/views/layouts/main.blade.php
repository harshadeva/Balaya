@include('includes.header_start')

@include('includes.common_styles')

@yield('psStyle') <!-- your custom styles goes here -->

@include('includes.header_end')

@include('includes.left_bar')

@include('includes.top_bar')

@yield('psContent') <!-- Page content goes here -->

@include('includes.footer_start')

@include('includes.common_scripts')

@yield('psScript') <!-- your custom scripts goes here -->

@include('includes.footer_end')
