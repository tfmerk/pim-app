<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use tfmerk\PolarisPim\Framework\ORM\Entity\EntityManager;
use tfmerk\PolarisPim\Entities\User;
use tfmerk\PolarisPim\Entities\Product;

try {
	echo 'Insert dummy data', PHP_EOL;

	$entityManager = EntityManager::createFromEnv();

	insertUser($entityManager, 'Bruce Wayne', 'bruce@wayne.goth', ['evil' => false, 'alias' => 'Batman', 'clearance' => 'max', 'skills' => ['martial arts', 'detective work', 'tactical strategy']]);
	insertUser($entityManager, 'Joker', 'why@so.serious', ['evil' => true, 'alias' => 'The Clown Prince', 'status' => 'Escaped', 'skills' => ['chemical engineering', 'psychological warfare', 'chaos']]);
	insertUser($entityManager, 'Poision Ivy', 'fl.ow@ers.goth', ['evil' => true, 'alias' => 'Pamela Isley', 'location' => 'Arkham', 'skills' => ['botany', 'toxicology', 'pheromones']]);
	insertUser($entityManager, 'Pinguin', 'pin.gu@in.goth', ['evil' => true, 'alias' => 'Oswald Cobblepot', 'business' => 'Iceberg Lounge', 'skills' => ['racketeering', 'firearms', 'umbrella modification']]);
	insertUser($entityManager, 'Selina Kyle', 'cat@burglar.goth', ['evil' => false, 'alias' => 'Catwoman', 'skills' => ['stealth', 'cracking', 'acrobatics']]);
	insertUser($entityManager, 'Alfred Pennyworth', 'alfred@wayne.goth', ['evil' => false, 'role' => 'Butler', 'military_background' => true, 'skills' => ['field medicine', 'culinary arts', 'covert logistics']]);
	insertUser($entityManager, 'James Gordon', 'jgordon@gcpd.gov', ['evil' => false, 'rank' => 'Commissioner', 'corrupt' => false, 'skills' => ['leadership', 'investigation', 'marksmanship']]);
	insertUser($entityManager, 'Harvey Dent', 'two@face.goth', ['evil' => true, 'alias' => 'Two-Face', 'obsession' => 'duality', 'skills' => ['prosecution law', 'coin tossing', 'extortion']]);
	insertUser($entityManager, 'Edward Nygma', 'riddle@me.this', ['evil' => true, 'alias' => 'The Riddler', 'IQ' => 190, 'skills' => ['cryptography', 'puzzle design', 'hacking']]);
	insertUser($entityManager, 'Dick Grayson', 'nightwing@bludhaven.org', ['evil' => false, 'alias' => 'Nightwing', 'acrobat' => true, 'skills' => ['trapeze', 'escapology', 'leadership']]);
	insertUser($entityManager, 'Bane', 'break@the.bat', ['evil' => true, 'home' => 'Santa Prisca', 'venom_dependent' => true, 'skills' => ['hand-to-hand combat', 'strategic planning', 'escapology']]);
	insertUser($entityManager, 'Victor Fries', 'sub@zero.goth', ['evil' => false, 'alias' => 'Mr. Freeze', 'condition' => 'cryogenic', 'motivation' => 'Nora', 'skills' => ['cryogenics', 'thermodynamics', 'heavy weaponry']]);
	insertUser($entityManager, 'Harleen Quinzel', 'puddin@madlove.goth', ['evil' => true, 'alias' => 'Harley Quinn', 'profession' => 'Psychiatrist', 'skills' => ['psychoanalysis', 'gymnastics', 'heavy brawling']]);
	insertUser($entityManager, 'Tim Drake', 'robin3@wayne.goth', ['evil' => false, 'alias' => 'Red Robin', 'detective_skill' => 95, 'skills' => ['computer science', 'bo staff', 'deduction']]);
	insertUser($entityManager, 'Barbara Gordon', 'oracle@clocktower.net', ['evil' => false, 'alias' => 'Oracle', 'intel_network' => 'global', 'skills' => ['cyber warfare', 'information brokerage', 'systems engineering']]);
	insertUser($entityManager, 'Waylon Jones', 'killer@croc.sewers', ['evil' => true, 'alias' => 'Killer Croc', 'mutation' => 'atavistic', 'skills' => ['underwater hunting', 'superhuman strength', 'tracking']]);
	insertUser($entityManager, 'Slade Wilson', 'contract@deathstroke.com', ['evil' => true, 'alias' => 'Deathstroke', 'rate_per_day' => 150000, 'skills' => ['swordsmanship', 'demolitions', 'assassination']]);
	insertUser($entityManager, 'Jonathan Crane', 'scarecrow@fear.goth', ['evil' => true, 'alias' => 'Scarecrow', 'specialty' => 'Phobias', 'skills' => ['psychopharmacology', 'fear induction', 'scythe combat']]);
	insertUser($entityManager, 'Lucius Fox', 'lfox@wayneenterprises.com', ['evil' => false, 'title' => 'CEO', 'division' => 'Applied Sciences', 'skills' => ['corporate finance', 'r&d management', 'advanced engineering']]);
	insertUser($entityManager, 'Ra\'s al Ghul', 'immortal@demonhead.org', ['evil' => true, 'organization' => 'League of Assassins', 'lazarus_pit_trips' => 14, 'skills' => ['swordsmanship', 'ancient alchemy', 'global subversion']]);

	insertProduct(
		$entityManager,
		'Batarang Deluxe',
		14.99,
		'https://cdn.polaris.local/batarang.png',
		'Perfect for dark nights in Gotham.',
		['material' => 'Titanium Alloy', 'weight_g' => 250]
	);

	insertProduct(
		$entityManager,
		'Joker Gas Canister',
		49.99,
		null,
		'Smiles guaranteed!',
		['dangerous' => true, 'color' => 'Green']
	);

	insertProduct(
		$entityManager,
		'Grapple Gun Mark IV',
		299.99,
		'https://cdn.polaris.local/grapple_gun.png',
		'Ascend skyscrapers effortlessly with high-tensile strength cable.',
		['max_range_m' => 150, 'max_weight_kg' => 400]
	);

	insertProduct(
		$entityManager,
		'The Batmobile Engine Upgrade',
		8500.00,
		null,
		'Twin-turbocharged afterburner assembly ready to outrun any threat.',
		['horsepower' => 1200, 'fuel' => 'Jet Propellant']
	);

	insertProduct(
		$entityManager,
		'Riddler Puzzle Box',
		19.95,
		'https://cdn.polaris.local/puzzle_box.png',
		'Contains secrets meant only for the brilliant. Can you unlock it?',
		['difficulty' => 'extreme', 'trapped' => true]
	);

	insertProduct(
		$entityManager,
		'Fear Toxin Vial',
		75.00,
		null,
		'Manifests the worst nightmares of anyone exposed.',
		['creator' => 'Jonathan Crane', 'state' => 'liquid']
	);

	insertProduct(
		$entityManager,
		'Kryptonite Ring Replica',
		1250.00,
		'https://cdn.polaris.local/k_ring.png',
		'An emerald radioactive precaution just in case a certain god goes rogue.',
		['radiation_type' => 'Gamma-Chrono', 'glow_in_dark' => true]
	);

	insertProduct(
		$entityManager,
		'Catwoman Steel Claws',
		35.50,
		null,
		'Diamond-tipped retractable climbing and combat gloves.',
		['material' => 'High-Carbon Steel', 'sharpness_rating' => 9.5]
	);

	insertProduct(
		$entityManager,
		'Freeze Gun Prototype',
		12500.00,
		'https://cdn.polaris.local/freeze_gun.png',
		'Sustains absolute zero operational beams via diamond-core magnification.',
		['coolant' => 'Liquid Nitrogen', 'range_m' => 45, 'requires_suit' => true]
	);

	insertProduct(
		$entityManager,
		'Venom Auto-Injector Wristband',
		4500.00,
		null,
		'Direct-to-bloodstream steroid distribution module.',
		['dosage_control' => 'digital', 'risk_factor' => 'addictive', 'potency' => 'maximum']
	);

	insertProduct(
		$entityManager,
		'Smoke Pellet Pack (x12)',
		24.99,
		'https://cdn.polaris.local/smoke_pellet.png',
		'Instantaneous ninja-grade optical concealment. Nineteen-second dispersal radius.',
		['chemical' => 'Anesthetic Blend', 'radius_m' => 5]
	);

	insertProduct(
		$entityManager,
		'Custom Harley Mallet',
		89.00,
		null,
		'Over-sized novelty structural demolition tool. Surprisingly well-balanced.',
		['weight_kg' => 12.5, 'material' => 'Solid Oak & Steel']
	);

	insertProduct(
		$entityManager,
		'Empirical Riddle Decoder Ring',
		149.00,
		'https://cdn.polaris.local/decoder_ring.png',
		'Decrypts encrypted regional radio networks used by the local police networks.',
		['frequency_range_mhz' => 450, 'encryption_cracking' => 'SHA-256-Fallback']
	);

	insertProduct(
		$entityManager,
		'EMP Grenade Mk II',
		620.00,
		null,
		'Disables all electronic components within a 30-meter burst area.',
		['charge_type' => 'High-Flux Capacitor', 'duration_min' => 15]
	);

	insertProduct(
		$entityManager,
		'WayneTech Tactical Cowl Assembly',
		3400.00,
		'https://cdn.polaris.local/cowl.png',
		'Kevlar-weave armored helmet integrated with sonar and night-vision arrays.',
		['audio_filters' => 'active-noise-cancelling', 'hud_os' => 'BatOS v4.12']
	);

	insertProduct(
		$entityManager,
		'Lazarus Pit Elixir Sample',
		999999.99,
		null,
		'Extremely volatile cellular regeneration fluid. Warning: May cause temporary psychosis.',
		['purity_percent' => 98.4, 'shelf_life' => 'indefinite']
	);
} catch (RuntimeException $t) {
	echo 'Configuration error: ' . $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . PHP_EOL;
} catch (InvalidArgumentException $t) {
	echo 'Validation error: ' . $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . PHP_EOL;
} catch (PDOException $t) {
	echo 'Database error: ' . $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . PHP_EOL;
} catch (Throwable $t) {
	echo 'Generic error: ' . $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine() . PHP_EOL;
}

function insertUser(EntityManager $entityManager, string $username, string $email, array $metadata): void
{
	echo 'Insert new user...', PHP_EOL;

	$user = new User(
		username: $username,
		email: $email,
		metadata: $metadata,
	);
	$entityManager->persist($user);
	echo 'User ID: ', $user->id, PHP_EOL;

	/** @var User|null $fetchedUser */
	$fetchedUser = $entityManager->find(User::class, $user->id);
	if ($fetchedUser !== null) {
		echo 'Fetched data:', PHP_EOL,
		'- Username: ', $fetchedUser->username, PHP_EOL,
		'- E-Mail: ', $fetchedUser->email, PHP_EOL,
		'- Created at: ', $fetchedUser->createdAt->format('Y-m-d H:i:s'), PHP_EOL,
		'- Metadata: ', json_encode($fetchedUser->metadata), PHP_EOL;
	}
	echo PHP_EOL;
}

function insertProduct(
	EntityManager $entityManager,
	string $productName,
	float $priceInDecimal,
	?string $imageUrl,
	?string $marketingText,
	array $metadata
): void {
	echo 'Insert new product...', PHP_EOL;

	// Convert decimal value (e.g. 14.99) safely to cents integer (1499)
	$priceInCents = (int)round($priceInDecimal * 100);

	$product = new Product(
		productName: $productName,
		price: $priceInCents,
		imageUrl: $imageUrl,
		marketingText: $marketingText,
		metadata: $metadata
	);

	$entityManager->persist($product);
	echo 'Product ID: ', $product->id, PHP_EOL;

	/** @var Product|null $fetchedProduct */
	$fetchedProduct = $entityManager->find(Product::class, $product->id);
	if ($fetchedProduct !== null) {
		echo 'Fetched data:', PHP_EOL,
		'- Product Name: ', $fetchedProduct->productName, PHP_EOL,
		'- Price (Raw Cents): ', $fetchedProduct->price, PHP_EOL,
		'- Price (Formatted): $', $fetchedProduct->getFormattedPrice(), PHP_EOL,
		'- Image URL: ', $fetchedProduct->imageUrl ?? 'None', PHP_EOL,
		'- Marketing Text: ', $fetchedProduct->marketingText ?? 'None', PHP_EOL,
		'- Created At: ', $fetchedProduct->createdAt->format('Y-m-d H:i:s'), PHP_EOL,
		'- Changed At: ', $fetchedProduct->changedAt->format('Y-m-d H:i:s'), PHP_EOL,
		'- Metadata: ', json_encode($fetchedProduct->metadata), PHP_EOL;
	}
	echo PHP_EOL;
}
