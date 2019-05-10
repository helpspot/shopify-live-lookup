# shopify-live-lookup
A Helpspot livelookup script for Shopify


# Shopify Setup
To set up the Shopify Live Lookup in HelpSpot you first need to obtain an API key and password for you Shopify Store. To do this:
1. Login to your store's admin area
2. Click on Apps
3. Select "Manage Private Apps"
4. Choose "Create Private App"
5. Enter the needed settings giving the app read access to your store
6. You will then be given the API key and password to use to authenticate the live lookup integration
# Livelookup setup
1. Download the live lookup script and open shopify.php in an editor
2. Specify a random string for the `$ll_key`. This adds basic security to the script to prevent unauthorized access to your customer information
3. Enter you store URL, api key and password in the variables at the top of the file:
```
//your store url without a trailing "/"
 $store_url = "https://mystoreurl.myshopify.com";
 //The api key and password are created when setting up the private app in the shopify settings
 $api_key = "yourapikeyhere";
 $api_pwd = "yourapipasswordhere";
 ```
 4. Save your changes to the custom_code folder in your HelpSpot installation.
 5. In the HelpSpot web admin area go to Admin > Settings > Live Lookup
 6. Add a new Live Lookup specifying a name, via GET and then the URL for your livelookup script. To build the URL we'll use the `$ll_key` set earlier. Example: `http://my.helpspot.url/custom_code/shopify.php?ll_key=qwerty123`

 Your Shopify Live Lookup will now allow you to lookup customer information. By default the search logic cascades through using the Customer ID, Email and finally the First and Last Name to find the customer. This search logic can be customized for your needs. 

The Shopify Live Lookup will display a number of customer information fields including links back to your shopify admin area. This can be all customized to your needs.
