<div class="sidebar__area">
    <div class="sidebar__close">
        <button class="close-btn">
            <i class="fa fa-close"></i>
        </button>
    </div>
    <div class="sidebar__brand">
        <a href="assets/dashboard">
            <img src="assets/images/logo/" alt="icon">
        </a>
    </div>
    <ul id="sidebar-menu" class="sidebar__menu">
        @if(isSuperAdmin())
        <li class="mm-active">
            <a href="{{ route('super_admin.dashboard')}}">
                <img src="assets/images/icons/sidebar/dashboard.svg" alt="icon">
                <span>Dashboard</span>
            </a>
        </li>
        <li class="">
            <a class="has-arrow" href="#">
                <i class="fas fa-user"></i>
                <span>Admin Manage</span>
            </a>
            <ul>
                <li class="">
                    <a href="{{ route('super_admin.admins.list') }}">
                        <i class="fa fa-circle"></i>
                        <span>Admin List</span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('super_admin.list')}}">
                        <i class="fa fa-circle"></i>
                        <span>Super Admin Lists</span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('super_admin.create.super_admin')}}">
                        <i class="fa fa-circle"></i>
                        <span>Create Super Admin</span>
                    </a>
                </li>

            </ul>
        </li>
        @endif
        <li class="">
            <a href="{{ route('admin.dashboard')}}">
                <img src="assets/images/icons/sidebar/dashboard.svg" alt="icon">
                <span>Dashboard</span>
            </a>
        </li>
        <li class="">
            <a href="">
                <i class="fa-solid fa-user"></i>
                <span>Profile</span>
            </a>
        </li>
        <li class="">
            <a href="{{ route('admin.profile.edit')}}">
                <i class="fa-solid fa-user"></i>
                <span>Edit Profile</span>
            </a>
        </li>
        <li class="">
            <a class="has-arrow" href="#">
                <i class="fas fa-list"></i>
                <span>Category and Brand</span>
            </a>
            <ul>
                <li class="">
                    <a href="category.html">
                        <i class="fa fa-circle"></i>
                        <span>Category</span>
                    </a>
                </li>
                <li class="">
                    <a href="brand.html">
                        <i class="fa fa-circle"></i>
                        <span>Brand</span>
                    </a>
                </li>

            </ul>
        </li>
        <li class="">
            <a class="has-arrow" href="#">
                <i class="fab fa-product-hunt"></i>
                <span>Products</span>
            </a>
            <ul>
                <li class="">
                    <a href="create-product.html">
                        <i class="fa fa-circle"></i>
                        <span>Add Product</span>
                    </a>
                </li>
                <li class="">
                    <a href="products.html">
                        <i class="fa fa-circle"></i>
                        <span>Product List</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="">
            <a class="has-arrow" href="#">
                <i class="fas fa-shopping-cart"></i>
                <span>Order Management</span>
            </a>
            <ul>
                <li class="">
                    <a href="orders.html">
                        <i class="fa fa-circle"></i>
                        <span>All Orders</span>
                        <span class="badge bg-info text-white">1</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="">
            <a href="transactions.html">
                <i class="fas fa-random"></i>
                <span>Transactions</span>
            </a>
        </li>
        <li class="">
            <a href="assets/country-tax-list">
                <i class="fas fa-percent"></i>
                <span>Tax Settings</span>
            </a>
        </li>
        <li class="">
            <a href="assets/delivery-charge-list">
                <i class="fas fa-shipping-fast"></i>
                <span>Delivery Charge</span>
            </a>
        </li>
        <li class="">
            <a href="coupon.html">
                <i class="fas fa-code"></i>
                <span>Coupon Code</span>
            </a>
        </li>
        <li class="">
            <a class="has-arrow" href="#">
                <i class="fas fa-blog"></i>
                <span>CRM</span>
            </a>
            <ul>
                <li class="">
                    <a href="contacts.html">
                        <i class="fa fa-circle"></i>
                        <span>Contact Us</span>
                    </a>
                </li>
                <li class="">
                    <a href="subscriber.html">
                        <i class="fa fa-circle"></i>
                        <span>Subscribers</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="">
            <a class="has-arrow" href="#">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            <ul>
                <li class="">
                    <a href="customers.html">
                        <i class="fa fa-circle"></i>
                        <span>Customer List</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="">
            <a class="has-arrow" href="#">
                <i class="fas fa-cube"></i>
                <span>CMS</span>
            </a>
            <ul>
                <li class="">
                    <a href="settings.html">
                        <i class="fa fa-circle"></i>
                        <span>General Settings</span>
                    </a>
                </li>
                <li class="">
                    <a href="home-settings.html">
                        <i class="fa fa-circle"></i>
                        <span>Home Page</span>
                    </a>
                </li>
                <li class="">
                    <a href="testimonial.html">
                        <i class="fa fa-circle"></i>
                        <span>Testimonial</span>
                    </a>
                </li>
                <li class="">
                    <a href="languages.html">
                        <i class="fa fa-circle"></i>
                        <span>Languages</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="">
            <a href="sliders.html">
                <i class="fas fa-list-ol"></i>
                <span>Sliders</span>
            </a>
        </li>
        <li class="">
            <a class="has-arrow" href="#">
                <i class="fas fa-cube"></i>
                <span>SEO Management</span>
            </a>
            <ul>
                <li class="">
                    <a href="seo-homepage.html">
                        <i class="fa fa-circle"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="">
                    <a href="seo-homepage.html">
                        <i class="fa fa-circle"></i>
                        <span>About Us</span>
                    </a>
                </li>
                <li class="">
                    <a href="seo-homepage.html">
                        <i class="fa fa-circle"></i>
                        <span>Contact</span>
                    </a>
                </li>
                <li class="">
                    <a href="seo-homepage.html">
                        <i class="fa fa-circle"></i>
                        <span>Products</span>
                    </a>
                </li>
                <li class="">
                    <a href="seo-homepage.html">
                        <i class="fa fa-circle"></i>
                        <span>Cart</span>
                    </a>
                </li>
                <li class="">
                    <a href="seo-homepage.html">
                        <i class="fa fa-circle"></i>
                        <span>Checkout</span>
                    </a>
                </li>
                <li class="">
                    <a href="seo-homepage.html">
                        <i class="fa fa-circle"></i>
                        <span>Wishlist</span>
                    </a>
                </li>
                <li class="">
                    <a href="seo-homepage.html">
                        <i class="fa fa-circle"></i>
                        <span>Compare</span>
                    </a>
                </li>
                <li class="">
                    <a href="seo-homepage.html">
                        <i class="fa fa-circle"></i>
                        <span>Sign In</span>
                    </a>
                </li>
                <li class="">
                    <a href="seo-homepage.html">
                        <i class="fa fa-circle"></i>
                        <span>Sign Up</span>
                    </a>
                </li>
                <li class="">
                    <a href="seo-homepage.html">
                        <i class="fa fa-circle"></i>
                        <span>Forget Password</span>
                    </a>
                </li>
                <li class="">
                    <a href="seo-homepage.html">
                        <i class="fa fa-circle"></i>
                        <span>Reset Password</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="">
            <a href="gateways.html">
                <i class="fa fa-money-bill"></i>
                <span>Payment Gateway</span>
            </a>
        </li>
        <li class="">
            <a class="has-arrow" href="#">
                <i class="fas fa-address-book"></i>
                <span>Company</span>
            </a>
            <ul>
                <li class="">
                    <a href="faq.html">
                        <i class="fa fa-circle"></i>
                        <span>FAQ</span>
                    </a>
                </li>
                <li class="">
                    <a href="pages.html">
                        <i class="fa fa-circle"></i>
                        <span>Privacy Policy</span>
                    </a>
                </li>
                <li class="">
                    <a href="pages.html">
                        <i class="fa fa-circle"></i>
                        <span>Terms &amp; Condition</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</div>