<?php
declare(strict_types = 1);

class Service {
    public int $id;
    public string $name;
    public string $description;
    public float $price;
    public string $created_at;
    public int $number_applications;
    public string $category;
    public int $buyer_id;
    public ?int $worker_id; // Worker is optional, can be null if not assigned yet

    // Constructor to initialize the service object
    public function __construct(int $id, string $name, string $description, float $price, string $created_at, int $number_applications, string $category, int $buyer_id, ?int $worker_id)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->created_at = $created_at;
        $this->number_applications = $number_applications;
        $this->category = $category;
        $this->buyer_id = $buyer_id;
        $this->worker_id = $worker_id;
    }

    // Method to save or update a service in the database
    function save(PDO $db) {
        if ($this->id) {
            // Update existing service
            $stmt = $db->prepare('
                UPDATE Service 
                SET name = ?, description = ?, price = ?, number_applications = ?, category = ?, buyer_id = ?, worker_id = ?
                WHERE id = ?
            ');
            $stmt->execute(array(
                $this->name, 
                $this->description, 
                $this->price, 
                $this->number_applications, 
                $this->category, 
                $this->buyer_id, 
                $this->worker_id,
                $this->id
            ));
        } else {
            // Insert new service
            $stmt = $db->prepare('
                INSERT INTO Service (name, description, price, created_at, number_applications, category, buyer_id, worker_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ');
            $created_at = date('Y-m-d H:i:s');
            $stmt->execute(array(
                $this->name, 
                $this->description, 
                $this->price, 
                $created_at, 
                $this->number_applications, 
                $this->category, 
                $this->buyer_id, 
                $this->worker_id
            ));
        }
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
