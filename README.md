# EcommerceBackend

To run this project follow steps :-

a)clone the project
b)run the command - composer install
c)copy env.example file to .env file (newly created file)
d)run the command - php artisan key:generate  to generate app key
e)setup database information in .env file
e)run the command - php artisan migrate
f)after successfull migration run the command - php artisan serve 
g)to access frontend part run the command - npm install     and     npm run dev
h) if any design issue will be occur then run the command - npm run build        and then run the command - npm run dev

i)for reset password ( email integration)->
( i have used here mailtrap)     if want to follow mailtrap   ->go to   "mailtrap.io"   ->create account->Go to email testing ->click on the email (in my case i have my inbox as email name)->Go to integration->Go to code samples ->select the language as laravel 9+ ->copy the credential and paste into ->env file (where the email information present)
