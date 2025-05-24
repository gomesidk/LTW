<?php
declare(strict_types = 1);

class Image {
    public int $id;
    public string $title;
    

    // Constructor to initialize the service object
    public function __construct(int $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    // Method to save or update a service in the database
    function save(PDO $db) {
        if ($this->id) {
            // Update existing service
            $stmt = $db->prepare('
                UPDATE Image
                SET title = ?
                WHERE id = ?
            ');
            $stmt->execute(array(
                $this->title, 
                $this->id
            ));
        } else {
            // Insert new service
            $stmt = $db->prepare('
                INSERT INTO Image (title)
                VALUES (?)
            ');
            $stmt->execute(array(
                $this->title, 
            ));
        }
    }

    static function getImage(PDO $db, int $id) : Image {
        $stmt = $db->prepare('
            SELECT id, title
            FROM Image
            WHERE id = ?
        ');
        $stmt->execute(array($id));
        $image = $stmt->fetch();
        
        return new Image(
            $image['id'],
            $image['title'],
        );
    }

    static function getImageByUser(PDO $db, int $user_id) : Image {
        $stmt = $db->prepare('
            SELECT profile_picture_id
            FROM User
            WHERE id = ?
        ');
        $stmt->execute(array($user_id));
        $image = $stmt->fetch();
        $image = new Image(
            $image['profile_picture_id'],
            ''
        );
        return $image;
    }

    static function incrementApplications(PDO $db, int $id) {
        $stmt = $db->prepare('
            UPDATE Service 
            SET number_applications = number_applications + 1
            WHERE id = ?
        ');
        $stmt->execute(array($id));
    }

    static function newApplication(PDO $db, int $service_id, int $worker_id) {
        $stmt = $db->prepare('
            INSERT INTO Application (service_id, user_id)
            VALUES (?, ?) 
        ');
        $stmt->execute(array($service_id, $worker_id));
    }

    static function isApplied(PDO $db, int $service_id, int $worker_id) : bool {
        $stmt = $db->prepare('
            SELECT COUNT(*) as count
            FROM Application
            WHERE service_id = ? AND user_id = ?
        ');
        $stmt->execute(array($service_id, $worker_id));
        $result = $stmt->fetch();
        
        return $result['count'] > 0;
    }

    // Static method to retrieve a service by ID
    static function getService(PDO $db, int $id) : Service {
        $stmt = $db->prepare('
            SELECT id, name, description, price, created_at, number_applications, category, buyer_id, worker_id
            FROM Service
            WHERE id = ?
        ');
        $stmt->execute(array($id));
        $service = $stmt->fetch();
        
        return new Service(
            $service['id'],
            $service['name'],
            $service['description'],
            $service['price'],
            $service['created_at'],
            $service['number_applications'],
            $service['category'],
            $service['buyer_id'],
            $service['worker_id']
        );
    }

    // Static method to retrieve all services for a specific buyer
    static function getServicesByBuyer(PDO $db, int $buyer_id) : array {
        $stmt = $db->prepare('
            SELECT id, name, description, price, created_at, number_applications, category, buyer_id, worker_id
            FROM Service
            WHERE buyer_id = ?
        ');
        $stmt->execute(array($buyer_id));
        $services = $stmt->fetchAll();

        $serviceObjects = [];
        foreach ($services as $service) {
            $serviceObjects[] = new Service(
                $service['id'],
                $service['name'],
                $service['description'],
                $service['price'],
                $service['created_at'],
                $service['number_applications'],
                $service['category'],
                $service['buyer_id'],
                $service['worker_id']
            );
        }

        return $serviceObjects;
    }

    // Static method to retrieve all services in a specific category
    static function getServicesByCategory(PDO $db, string $category) : array {
        $stmt = $db->prepare('
            SELECT id, name, description, price, created_at, number_applications, category, buyer_id, worker_id
            FROM Service
            WHERE category = ?
        ');
        $stmt->execute(array($category));
        $services = $stmt->fetchAll();

        $serviceObjects = [];
        foreach ($services as $service) {
            $serviceObjects[] = new Service(
                $service['id'],
                $service['name'],
                $service['description'],
                $service['price'],
                $service['created_at'],
                $service['number_applications'],
                $service['category'],
                $service['buyer_id'],
                $service['worker_id']
            );
        }

        return $serviceObjects;
    }

    // Static method to retrieve all services in a specific category
    static function getServices(PDO $db) : array {
        $stmt = $db->prepare('
            SELECT id, name, description, price, created_at, number_applications, category, buyer_id, worker_id
            FROM Service
        ');
        $stmt->execute(array());
        $services = $stmt->fetchAll();

        $serviceObjects = [];
        foreach ($services as $service) {
            $serviceObjects[] = new Service(
                $service['id'],
                $service['name'],
                $service['description'],
                $service['price'],
                $service['created_at'],
                $service['number_applications'],
                $service['category'],
                $service['buyer_id'],
                $service['worker_id']
            );
        }

        return $serviceObjects;
    }

    static function workerPicked(PDO $db, int $service_id, int $worker_id) {
        $stmt = $db->prepare('
            UPDATE Service 
            SET worker_id = ?
            WHERE id = ?
        ');
        $stmt->execute(array($worker_id, $service_id));
    }

    static function deleteService(PDO $db, int $service_id) {
        $stmt = $db->prepare('
            DELETE FROM Service 
            WHERE id = ?
        ');
        $stmt->execute(array($service_id));
    }

    static function setworker(PDO $db, int $service_id, int $worker_id) {
        $stmt = $db->prepare('
            UPDATE Service 
            SET worker_id = ?
            WHERE id = ?
        ');
        $stmt->execute(array($worker_id, $service_id));
    }
}
?>
