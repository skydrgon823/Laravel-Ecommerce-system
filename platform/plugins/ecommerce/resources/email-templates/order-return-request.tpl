{{ header }}

<h2>New order return request!</h2>

<p>Hi, {{ customer_name }} has just requested return product(s) on your site.</p>

{{ list_order_products }}

<h3>Return reason:</h3>
<p>{{ return_reason }}</p>

<h3>Customer information</h3>

<p>{{ customer_name }} - {{ customer_phone }}, {{ customer_address }}</p>

{{ footer }}
