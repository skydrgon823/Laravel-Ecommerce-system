{{ header }}

<h2>Congratulation, you have a new order on {{ site_title }}!</h2>

<p>Hi, {{ customer_name }} has just ordered on your site</p>

{{ product_list }}

<h3>Customer information</h3>

<p>{{ customer_name }} - {{ customer_phone }}, {{ customer_address }}</p>

<h3>Shipping method</h3>
<p>{{ shipping_method }}</p>

<h3>Payment method</h3>
<p>{{ payment_method }}</p>

{{ footer }}
