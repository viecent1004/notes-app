const express = require('express');
const mysql = require('mysql2');
const bodyParser = require('body-parser');
const cors = require('cors');

const app = express();
const port = 3000;

app.use(cors()); 
app.use(bodyParser.json()); 

// MySQL 
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'notes'
});

// connect to MySQL
db.connect((err) => {
  if (err) {
    console.error('error', err.message);
    process.exit(1); //exit the process if connection fails
  }
  console.log('successfully connected to the database');
});

// 创建
app.post('/notes', (req, res) => {
  const { title, content } = req.body;
  if (!title || !content) {
    return res.status(400).json({ message: 'title cannot be empty' });
  }
  const query = 'INSERT INTO notes (title, content) VALUES (?, ?)';
  db.query(query, [title, content], (err, results) => {
    if (err) {
      console.error('failed to create:', err.message);
      return res.status(500).json({ message: 'failed to create' });
    }
    res.status(201).json({
      message: 'suuccessfully created',
      note: { id: results.insertId, title, content }
    });
  });
});

//获取笔记
app.get('/notes', (req, res) => {
  const query = 'SELECT * FROM notes';
  db.query(query, (err, results) => {
    if (err) {
      console.error('fail checked', err.message);
      return res.status(500).json({ message: 'fail checked' });
    }
    res.json(results);
  });
});

// 更新笔记
app.put('/notes/:id', (req, res) => {
  const { id } = req.params;
  const { title, content } = req.body;
  if (!title || !content) {
    return res.status(400).json({ message: 'title cannot be empty' });
  }
  const query = 'UPDATE notes SET title = ?, content = ? WHERE id = ?';
  db.query(query, [title, content, id], (err, results) => {
    if (err) {
      console.error('failed update', err.message);
      return res.status(500).json({ message: 'failed update' });
    }
    if (results.affectedRows === 0) {
      return res.status(404).json({ message: 'not found' });
    }
    res.json({ message: 'successfully renew' });
  });
});

//删除
app.delete('/notes/:id', (req, res) => {
  const { id } = req.params;
  const query = 'DELETE FROM notes WHERE id = ?';
  db.query(query, [id], (err, results) => {
    if (err) {
      console.error('delete failed:', err.message);
      return res.status(500).json({ message: 'delete failed' });
    }
    if (results.affectedRows === 0) {
      return res.status(404).json({ message: 'not found' });
    }
    res.json({ message: 'successfullly delete' });
  });
});

// 启动
app.listen(port, () => {
  console.log(`http://localhost:${port}/notes`);
});
