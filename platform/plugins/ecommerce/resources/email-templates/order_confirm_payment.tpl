{{ header }}

<h2>Payment for order #{{ order_id }} was confirmed!</h2>
<p>Hi {{ customer_name }},</p>
<p>Thank you for purchasing our product.</p>

{{ product_list }}

<h3>Customer information</h3>

<p>{{ customer_name }} - {{ customer_phone }}, {{ customer_address }}</p>

<h3>Shipping method</h3>
<p>{{ shipping_method }}</p>

<h3>Payment method</h3>
<p>{{ payment_method }}</p>

<br />

<p>If you have any question, please contact us via <a href="mailto:{{ site_admin_email }}">{{ site_admin_email }}</a></p>

{{ footer }}
