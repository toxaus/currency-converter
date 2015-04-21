Currency converter
==================
Get currency exchange rates & convert amount between currencies in ISO Alpha 3 format.
There are some popular API was implemented.
 - Yahoo Finance (Yahoo currency exchange API);
 - Free (details http://www.freecurrencyconverterapi.com);
 - fixer.io (details http://fixer.io/).

<i>Notice: All currencies must be passed in ISO Alpha 3 format (ex. "USD" for American Dollar)</i><br>
For get currency rates:
<pre>
  $converter = new Yahoo();
  $converter->rate("USD", "EUR");
</pre>
For convert amount:
<pre>
  $converter = new Yahoo();
  $converter->convert("USD", "EUR", 10);
</pre>
