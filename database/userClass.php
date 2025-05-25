<?php
declare(strict_types = 1);

class User {
    public int $id;
    public string $name;
    public string $email;
    public string $password;
    public string $created_at;
    public int $level;
    public string $birth_date;
    
    public string $phone;
    public string $nr_bank_account;
    public string $address;
    public ?string $type_of_service;
    public int $rate;
    public ?string $description;
    public string $user_type;
    public ?int $profile_picture_id;  // Added property

    public function __construct(
        int $id,
        string $name,
        string $email,
        string $password,
        string $created_at,
        int $level,
        string $birth_date,
        string $phone,
        string $nr_bank_account,
        string $address,
        int $rate = 0,
        ?string $description,
        string $user_type,
        ?int $profile_picture_id = null  // New constructor param with default
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->created_at = $created_at;
        $this->level = $level;
        $this->birth_date = $birth_date;

        $this->phone = $phone;
        $this->nr_bank_account = $nr_bank_account;
        $this->address = $address;
        $this->rate = $rate;
        $this->description = $description;
        $this->user_type = $user_type;
        $this->profile_picture_id = $profile_picture_id;  // Assign new property
    }

    function name() {
        return $this->name;
    }

    function save(PDO $db) {
        $stmt = $db->prepare('
            UPDATE User 
            SET name = ?, email = ?, password = ?, created_at = ?, level = ?, birth_date = ?, phone = ?, nr_bank_account = ?, address = ?, rate = ?, description = ?, user_type = ?, profile_picture_id = ?
            WHERE id = ?
        ');
        $stmt->execute(array(
            $this->name,
            $this->email,
            $this->password,
            $this->created_at,
            $this->level,
            $this->birth_date,

            $this->phone,
            $this->nr_bank_account,
            $this->address,
            $this->rate,
            $this->description,
            $this->user_type,
            $this->profile_picture_id,  // Added here
            $this->id
        ));
    }

    static function getUserWithPassword(PDO $db, string $identifier, string $password) : ?User {
        $stmt = $db->prepare('
            SELECT id, name, email, password, created_at, level, birth_date, phone, nr_bank_account, address, rate, description, user_type, profile_picture_id
            FROM User
            WHERE (email = ? OR name = ? OR phone = ?)
              AND password = ?
        ');
    
        $hashedPassword = sha1($password);
    
        $stmt->execute([$identifier, $identifier, $identifier, $hashedPassword]);
    
        if ($user = $stmt->fetch()) {
            return new User(
                $user['id'],
                $user['name'],
                $user['email'],
                $user['password'],
                $user['created_at'],
                $user['level'],
                $user['birth_date'],
                $user['phone'],
                $user['nr_bank_account'],
                $user['address'],
                $user['rate'],
                $user['description'],
                $user['user_type'],
                $user['profile_picture_id'] ?? null  // Pass profile_picture_id
            );
        } else {
            return null;
        }
    }
    
    static function getUser(PDO $db, int $id) : ?User {
        $stmt = $db->prepare('
            SELECT id, name, email, password, created_at, level, birth_date, phone, nr_bank_account, address, rate, description, user_type, profile_picture_id
            FROM User 
            WHERE id = ?
        ');
        $stmt->execute(array($id));
        $user = $stmt->fetch();

        if (!$user) return null;

        return new User(
            $user['id'],
            $user['name'],
            $user['email'],
            $user['password'],
            $user['created_at'],
            $user['level'],
            $user['birth_date'],

            $user['phone'],
            $user['nr_bank_account'],
            $user['address'],
            $user['rate'],
            $user['description'],
            $user['user_type'],
            $user['profile_picture_id'] ?? null  // Pass profile_picture_id
        );
    }

    // Static method to register a new user
    static function register(PDO $db, string $name, string $email, string $password, string $birth_date, string $phone, string $nr_bank_account, string $address): bool {
        $created_at = date('d-m-Y');
        $stmt = $db->prepare('
            INSERT INTO User (
                name, email, password, created_at, level,
                birth_date, phone, nr_bank_account, address,
                rate, description, user_type, profile_picture_id
            ) VALUES (?, ?, ?, ?, 1, ?, ?, ?, ?, 0, NULL, "user", NULL)
        ');

        $hashedPassword = sha1($password);
        return $stmt->execute([
            $name, $email, $hashedPassword, $created_at,
            $birth_date, $phone, $nr_bank_account, $address
        ]);
    }

    static function get_Users_By_Service(PDO $db, int $service_id) : array {
        $stmt = $db->prepare('
            SELECT User.id, User.name, User.email, User.password, User.created_at, User.level, User.birth_date,
                   User.phone, User.nr_bank_account, User.address, User.rate, User.description, User.user_type, User.profile_picture_id
            FROM User
            JOIN Application ON Application.user_id = User.id
            WHERE Application.service_id = ?
        ');
        $stmt->execute([$service_id]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $userObjects = [];
        foreach ($users as $user) {
            $userObjects[] = new User(
                $user['id'],
                $user['name'],
                $user['email'],
                $user['password'],
                $user['created_at'],
                $user['level'],
                $user['birth_date'],
                $user['phone'],
                $user['nr_bank_account'],
                $user['address'],
                $user['rate'],
                $user['description'],
                $user['user_type'],
                $user['profile_picture_id'] ?? null
            );
        }
    
        return $userObjects;
    }

    static function rateUser(PDO $db, int $user_id, int $rating) : bool {
        // Ensure rating is between 0 and 5
        if ($rating < 0 || $rating > 5) {
            return false; // Invalid rating
        }

        $stmt = $db->prepare('
            UPDATE User 
            SET rate = rate + ?
            WHERE id = ?
        ');
        return $stmt->execute([$rating, $user_id]);
    }

    static function upgradeUser(PDO $db, int $user_id): bool {
        $stmt = $db->prepare('
            UPDATE User 
            SET level = level + 1
            WHERE id = ?
        ');
        return $stmt->execute([$user_id]);
    }
    // Promote a user to admin
    static function promoteToAdmin(PDO $db, int $user_id): bool {
        $stmt = $db->prepare('UPDATE User SET user_type = "admin" WHERE id = ?');
        return $stmt->execute([$user_id]);
    }

    // Delete a user by ID
    static function deleteUser(PDO $db, int $user_id): bool {
        $stmt = $db->prepare('DELETE FROM User WHERE id = ?');
        return $stmt->execute([$user_id]);
    }

    
}
?>
