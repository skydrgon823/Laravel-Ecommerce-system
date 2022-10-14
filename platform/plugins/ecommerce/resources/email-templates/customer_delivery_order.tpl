{{ header }}

<h2>Your order is delivering!</h2>

<p>Hi {{ customer_name }},</p>

<p>Your products are on the way.</p>

{{ order_delivery_notes }}

<p>If you have any question, please contact us via <a href="mailto:{{ site_admin_email }}">{{ site_admin_email }}</a></p>

{{ footer }}
