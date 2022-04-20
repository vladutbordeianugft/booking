## Booking API



### Set things up
1. Edit `config/defines.php` and change `BASE_URL` with the new url where the app will be running.
2. Set up mysql database credentials on the followings files:
`/config/db.php`
`/config/phinx.php` (used for database migrations, i used the development env)
3. Install dependencies by going to `composer install`
4. Create tables in your database by going to `vendor/bin/phinx migrate -e development`

### Test APP API end points (add users and trips)
I used [postman][postman] app to make api requests

###### USER API 
- create user: `/api/user/create` (fields: first_name, last_name, email, password)
- login user: `/api/user/login` (fields: email, password)

###### TRIP API 
- create trip: `/api/trip/create` (fields: title, description, start_date, end_date, location, price)
-- start_date and end_date must be datetime format (YYYY-MM-DD HH:MM:SS)
- get all trips: `/api/trip/read` (filters fields if need: search, orderBy, priceRange)
-- `search`: any string (will search on trip title using 'like' method. We can use full text search for better search relevance)
-- `orderBy`: price-asc, price-desc, start-date-asc, start-date-end
-- `priceRange`: 0-99999 (e.g: 1-90, will return prices starting from 1 to 90)
- get trip by slug: `/api/trip/read` (fields: slug)

###### BOOKING API 
- book trip: `/api/trip/book` (fields: id)
Note: id is the trip id. User must be logged in.




### Test APP in browser (login and book functionality)
- Enter `https://your_app_url/`




[postman]: <https://www.postman.com/>
  
