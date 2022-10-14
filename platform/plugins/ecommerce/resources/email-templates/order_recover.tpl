{{ header }}

<h2>Order is waiting for you to complete!</h2>

<p>Hi {{ customer_name }},</p>
<p>We noticed you were intending to buy some products in our store, would you like to continue?</p>

<a href="{{ site_url }}/checkout/{{ order_token }}/recover" class="button button-blue">Complete order</a> or <a href="{{ site_url }}">Go to our shop</a>

<br />

<h3>Product(s) in cart</h3>

{{ product_list }}

<br />

<p>If you have any question, please contact us via <a href="mailto:{{ site_admin_email }}">{{ site_admin_email }}</a></p>

{{ footer }}
