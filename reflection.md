1) Explain what require_login() does in index.php. When does it run, and what does it enforce?

        The require_login() function runs immediately upon opening the page and every time a new partial is requested. This is because of the if-statements that check to see if a post request has been made (which happens right away thanks to the $action variable) and/or if a login has not been detected within the session. This ensures that no partial will be loaded except for the login_form.php IF a user hasn't logged in.


2) Describe the login process step-by-step: from clicking the “Login” button on the form to the moment the user is redirected. Which file and which case handles the logic? What session variables are set after a successful login?

        When attempting to log in, the user must navigate to the login partial (which happens automatically if they're not already logged in), which is handeled by the switch statement in index.php. Then the user must input their username and password that is already registered within the database. After pressing the 'Login' button, a POST request gets sent, and the 'login' switch case takes that data in, setting it to the variables of $username and $password. These variables then get passed into the user_find_by_username() function, which locates the user and returns their infromation, and if the username and password match to what's in the database (done with an if-statement in the 'login' switch case), then the session is allowed to continue.

3) When you click “Add to Cart,” what exactly gets stored in $_SESSION['cart]? Which action adds items to the cart, and what type of data is being stored?

        $_SESSION['cart'] gets filled with the record id of the record that the user added with "Add to Cart." The action required is the 'add_to_cart' POST, and the type of data stored is the id integer in the 'records' table of the database.


4) On the cart page, you use $records_in_cart. Where does that variable come from, and why do we need records_by_ids() instead of just using the raw IDs in the session?

        'records_in_cart' comes directly from the 'index.php' page, and is filled by using the records_by_ids() function. This function is used because it pulls data from two different tables instead of just one, requiring a specific select statement to be run on the database. This also ensures that all records will be obtained instead of just some of them.


5) Explain what happens when you click “Complete Purchase.” Which action in index.php runs, what loop is executed, which function writes each record to the database, and which table is updated?

        When clicking "Complete Purchase," the variable $cart_ids gets filled with all of the record ids given in the post request (aka $_SESSION['cart']). Then it'll check if $cart_ids is empty, and if not, a foreach loop will run the purchase_create() function for each id given. In the function, the passed id will be added to the 'purchases' table within the databse along with the users id and current time. Finally, the initial if-statement empties the $_SESSION['cart'] when all ids given have been passed through, loading the 'checkout_success' view when complete. 