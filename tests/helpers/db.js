const mysql = require('mysql2/promise');

const DB_CONFIG = {
  host: '127.0.0.1',
  port: 3306,
  user: 'root',
  password: 'tiger',
  database: 'elogicnewdb',
};

async function getConnection() {
  return mysql.createConnection(DB_CONFIG);
}

async function query(sql, params = []) {
  const conn = await getConnection();
  try {
    const [rows] = await conn.execute(sql, params);
    return rows;
  } finally {
    await conn.end();
  }
}

module.exports = { getConnection, query };
