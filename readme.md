Inventory management with "learning capabilities".
SCRIPT LEARNING REASONING:
1) Check if the barcode (EAN) is already in the main database, if true then import it in local database, if false goto step 2
2) Check if the scraping script is able to fetch the result from the web, looking up to 1000 pages from google. If it finds the result it writes it in the main database and then imports it in the local database else If even this step result in a faliure  got to step 3
3) Ask for user to input the name and the price of the product, save it locally and then saves it in a temporary database, if more input from different users are submitted and they are the same then it can be imported in the main database
