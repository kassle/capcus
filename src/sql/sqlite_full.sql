CREATE TABLE items (
	code VARCHAR[128] PRIMARY KEY,
	owner VARCHAR[256],
	create_time VARCHAR[25],
	access_time VARCHAR[25],
	access_count INTEGER,
	source VARCHAR[2560]
);
