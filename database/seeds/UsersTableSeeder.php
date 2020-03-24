<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {
    private $photoPath = 'public/fotos';
    private $admins = 2;
    private $gestores = 2;
    private $files = [];
    static public $allUsers = [];

    private function limparFicheirosFotos() {
        Storage::deleteDirectory($this->photoPath);
        Storage::makeDirectory($this->photoPath);
    }

    private function preencherNomesFicheirosFotos() {
        $allFiles = collect(File::files(database_path('seeds/fotos')));
        foreach ($allFiles as $f) {
            $this->files[] = $f->getPathname();
        }
    }

    private function newFakerUser($faker, $tipo = 'e', $userByNumber = 0) {
        switch ($tipo) {
        	case 'a':
        		$fullname = 'admin' . $userByNumber;
        		break;
        	case 'g':
        		$fullname = 'gestor' . $userByNumber;
        		break;
        }
        $email = $fullname . '@mail.pt';

        return [
            'nome' => $fullname,
            'email' =>  $email,
            'password' => bcrypt('123'),
            'remember_token' => $faker->asciify('**********'),
            'tipo' => $tipo,
            'fotografia' => null,
        ];
    }

    private function insertUser($user) {
    	$userInfo = new ArrayObject($user);
    	$newId = DB::table('users')->insertGetId($user);
    	$userInfo['id'] = $newId;
    	UsersTableSeeder::$allUsers[$newId] = $userInfo;
    }

    private function gravarFoto($id, $file) {
        $targetDir = storage_path('app/'.$this->photoPath);
        //$sourceDir = database_path('seeds/fotos'); 
        $newfilename = $id . "_" . uniqid(). '.jpg';
        File::copy( $file, $targetDir.'/'.$newfilename);
        DB::table('users')->where('id', $id)->update(['fotografia' => $newfilename]);
        $this->command->info("Updated Photo of User $id. File $file copied as $newfilename");
    }

    private function updateFotos() {
    	$allUserIds = array_keys(UsersTableSeeder::$allUsers);
    	$totalFixos = 7;
    	while ($totalFixos > 0) {
    		$id = array_shift($allUserIds);
    		$fileName = array_shift($this->files);
    		$this->gravarFoto($id, $fileName);
    		$totalFixos--;    		
    	}
        shuffle($allUserIds);
        while ((count($allUserIds) > 0) && ((count($this->files)>0))) {
            $id = array_shift($allUserIds); 
            $user = UsersTableSeeder::$allUsers[$id];
            if (count($this->files)>0) {
                $fileName = array_shift($this->files);
                $this->gravarFoto($user["id"], $fileName);
            }
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->command->table(['Users table seeder notice'], [
            ['Photos will be stored on path '.storage_path('app/'.$this->photoPath)]
        ]);

        $this->limparFicheirosFotos();
        $this->preencherNomesFicheirosFotos();

        $faker = \Faker\Factory::create('pt_PT');

        for ($i=1; $i<= $this->admins; $i++) {        	
        	$userRow = $this->newFakerUser($faker, 'a', $i);
        	$this->insertUser($userRow);
            $this->command->info("Created User 'Administrator' - $i / " . $this->admins);
        }        

        for ($i=1; $i<= $this->gestores; $i++) {
        	$userRow = $this->newFakerUser($faker, 'g', $i);
        	$this->insertUser($userRow);
            $this->command->info("Created User 'Gestor' - $i / " . $this->gestores);
        }        
        $this->updateFotos();
    }
}
