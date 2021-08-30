# MegaTik Query Builder PHP



# Methods

* [select](#select)
* [select functions (min, max, count, avg, sum)](#select-functions-min-max-count-avg-sum)
* [table](#table)
* [get and first](#get-and-first)
* [join](#join)
* [where](#where)
* [where in](#where-in)
* [between](#between)
* [like](#like)
* [groupBy](#groupby)
* [having](#having)
* [orderBy](#orderby)
* [limit](#limit)
* [offset](#offset)
* [pagination](#pagination)
* [insert](#insert)
* [update](#update)
* [delete](#delete)

## Methods

### select
```php
DB::table('users')->select();
# sql: "SELECT * FROM users"

DB::table('users')->select('name, email');
# sql: "SELECT name, email FROM users"

DB::table('users')->select(['name', 'email']);
# sql: "SELECT name, email FROM users"
```

### select functions (min, max, count, avg, sum)
```php
DB::table('users')->max('follows');
# sql: "SELECT max(follows) FROM users"

DB::table('users')->sum('star');
# sql: "SELECT sum(star) FROM users"
```

### table
```php
DB::table('users');
# sql: "SELECT * FROM users"

DB::table('users, roles');
# sql: "SELECT * FROM users, roles"

DB::table(['users', 'roles']);
# sql: "SELECT * FROM users, roles"

DB::table('users AS user');
# sql: "SELECT * FROM users AS user"

# paremeter 2 hanya bisa digunakan jika kedua perameter string
DB::table('users', 'user');
# sql: "SELECT * FROM users AS user"
```

### get and first
```php
# get -> menampilkan semuanya
# first -> menampilkan 1

DB::table('users')->get();
# sql: "SELECT * FROM users"

DB::table('users')->first();
# sql: "SELECT * FROM users LIMIT 1"
```

### join
Peringatan!: Mohon perhatikan jika menggunakan prefix apabila table menggunkan AS (alias) maka contohnya ht_users AS u dan ht_posts AS p. Kami berharap ini dapat membantu jika ada kendala saat menggunakan prefix dan join.

```php
# Jika menggunakan prefix
DB::table('users AS u')->join('posts AS p', 'u.id', 'p.user_id')->get();
# sql: "SELECT * FROM ht_users AS u JOIN ht_posts AS p ON u.id = p.user_id"

# Tidak menggunakan prefix
DB::table('users AS u')->join('posts AS p', 'u.id', 'p.user_id')->get();
# sql: "SELECT * FROM users AS u JOIN posts AS p ON u.id = p.user_id"

# Jika menggunakan prefix dan tidak menggunakan AS (alias)
DB::table('users')->join('posts', 'users.id', 'posts.user_id')->get();
# sql: "SELECT * FROM ht_users JOIN ht_posts ON ht_users.id = ht_posts.user_id"
```

Ada 7 Method join

* join()
* leftJoin()
* rightJoin()
* leftOuterJoin()
* rightOuterJoin()
* fullOuterJoin()

```php
DB::table('test')->join('check', 'test.id', 'check.test_id')->get();
# sql: "SELECT * FROM test JOIN check ON test.id = check.test_id"

DB::table('test')->leftJoin('check', 'test.id', 'check.test_id')->get();
# sql: "SELECT * FROM test LEFT JOIN check ON test.id = check.test_id"

DB::table('test')->fullOuterJoin('check', 'test.id', '=', 'check.test_id')->get();
# sql: "SELECT * FROM test FULL OUTER JOIN check ON test.id = check.test_id"
```

### where
```php
DB::table('users')->where(1)->get();
# sql: "SELECT * FROM users WHERE id = 1"

DB::table('users')->where('status', 1)->get();
# sql: "SELECT * FROM users WHERE status = 1"

DB::table('users')->where('age', '>', 18)->get();
# sql: "SELECT * FROM users WHERE age > 18"

DB::table('users')->where([['id', 1], ['status', 1]])->get();
# sql: "SELECT * FROM users WHERE id = 1 AND status = 1"

DB::table('users')->where([['id', 1], ['status', 1]], 'OR')->get();
# sql: "SELECT * FROM users WHERE id = 1 OR status = 1"
```

### where in
```php
DB::table('test')->whereIn('id', [1, 2, 3])->get();
# sql: "SELECT * FROM test WHERE id IN (1, 2, 3)"

DB::table('test')->whereNotIn('id', [1, 2, 3])->get();
# sql: "SELECT * FROM test WHERE id NOT IN (1, 2, 3)"
```

### between
```php
DB::table('test')->between('age', 17, 25)->get();
# sql: "SELECT * FROM test WHERE age BETWEEN 17, 25"

DB::table('test')->between('age', [17, 25])->get();
# sql: "SELECT * FROM test WHERE age BETWEEN 17, 25"

DB::table('test')->notBetween('age', [17, 25])->get();
# sql: "SELECT * FROM test WHERE age NOT BETWEEN 17, 25"
```

### like
```php
DB::table('test')->like('name', '%example%')->get();
# sql: "SELECT * FROM test WHERE name LIKE %example%"

DB::table('test')->notLike('name', '%example%')->get();
# sql: "SELECT * FROM test WHERE name NOT LIKE %example%"
```

### groupBy
```php
DB::table('test')->groupBy('id')->get();
# sql: "SELECT * FROM test GROUP BY id"

DB::table('test')->groupBy(['id', 'user_id'])->get();
# sql: "SELECT * FROM test GROUP BY id, user_id"
```

### having
```php
DB::table('test')->having('COUNT(user_id)', 5)->get();
# sql: "SELECT * FROM test HAVING COUNT(user_id) > 5"

DB::table('test')->having('COUNT(user_id)', '>=', 5)->get();
# sql: "SELECT * FROM test HAVING COUNT(user_id) >= 5"

DB::table('test')->having('COUNT(user_id) > ?', [2])->get();
# sql: "SELECT * FROM test HAVING COUNT(user_id) >= 5"
```

### orderBy
```php
DB::table('test')->orderBy('name')->get();
# sql: "SELECT * FROM test ORDER BY name ASC"

DB::table('test')->orderBy('name', 'DESC')->get();
# sql: "SELECT * FROM test ORDER BY name DESC"
```

### limit
```php
DB::table('test')->limit(10)->get();
# sql: "SELECT * FROM test LIMIT 10"

DB::table('test')->limit(10, 20)->get();
# sql: "SELECT * FROM test LIMIT 10, 20"
```

### offset
```php
DB::table('test')->offset(10)->get();
# sql: "SELECT * FROM test OFFSET 10"
```

### pagination
```php
DB::table('test')->pagination(10, 1)->get();
# sql: "SELECT * FROM test LIMIT 10 OFFSET 0"

DB::table('test')->pagination(10, 2)->get();
# sql: "SELECT * FROM test LIMIT 10 OFFSET 10"
```

### insert
```php
$data = [
    'username' => 'febrihidayan',
    'status' => 1
];

DB::table('users')->insert($data);
# sql: "INSERT INTO users(username, status) VALUES('febrihidayan', 1)"
```

### update
```php
$data = [
    'username' => 'febrihidayan',
    'status' => 1
];

DB::table('users')->where(1)->update($data);
# sql: "UPDATE users SET username = 'febrihidayan', status = 1 WHERE id = 1"
```

### delete
```php
DB::table('users')->where(1)->delete();
# sql: "DELETE FROM users WHERE id = 1"
```
