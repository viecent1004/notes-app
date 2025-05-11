<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Note Pad</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }
    .form {
      margin-bottom: 20px;
      display: flex;
      flex-direction: column;
    }
    .input {
      padding: 10px;
      height: 40px;
      width: 100%;
      font-size: 16px;
      margin-top: 5px;
      resize: none;
      overflow: hidden;
    }
    .input-title {
      height: 40px;
    }
    .input-content {
      height: 100px;
      overflow-y: auto;
    }
    .submit, .cancel {
      padding: 10px 15px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 10px;
      margin-right: 10px;
    }
    .submit {
      background-color: rgb(9, 156, 9);
      color: white;
      border: none;
    }
    .cancel {
      background-color: rgb(244, 42, 42);
      color: white;
      border: none;
    }
    .table {
      width: 100%;
      margin-top: 20px;
      border: 2px solid black;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid black;
      padding: 8px;
      text-align: left;
    }
    caption {
      font-size: 24px;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <div id="app">
    <header>
      <h1>Notes App</h1>
    </header>

    <!-- 表单 -->
    <form @submit.prevent="isEditing ? updateNote() : addNote()" class="form">
      <label for="title">Title:</label>
      <input v-model="newNote.title" id="title" placeholder="Title" required class="input input-title" />

      <label for="content">Content:</label>
      <textarea v-model="newNote.content" id="content" placeholder="Content" required class="input input-content"></textarea>

      <button type="submit" class="submit">{{ isEditing ? 'Update Note' : 'Add Note' }}</button>
      <button v-if="isEditing" @click.prevent="cancelEdit" class="cancel">Cancel</button>
    </form>

    <!-- 笔记列表 -->
    <table class="table">
      <caption>Notes List</caption>
      <thead>
        <tr>
          <th>Title</th>
          <th>Content</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="note in notes" :key="note.id">
          <td>{{ note.title }}</td>
          <td>{{ note.content }}</td>
          <td>
            <button @click="editNote(note)">Edit</button>
            <button @click="deleteNote(note.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <script>
    new Vue({
      el: '#app',
      data: {
        notes: [],
        newNote: { title: '', content: '' },
        isEditing: false,
        editingNoteId: null
      },
      mounted() {
        this.fetchNotes();
      },
      methods: {
        fetchNotes() {
          axios.get('http://localhost:3000/notes')
            .then(response => {
              this.notes = response.data;
            })
            .catch(error => {
              console.error('获取笔记失败:', error);
              alert('无法加载笔记，请检查后端服务');
            });
        },
        addNote() {
          axios.post('http://localhost:3000/notes', this.newNote)
            .then(response => {
              this.newNote = { title: '', content: '' };
              this.fetchNotes();
              alert(response.data.message);
            })
            .catch(error => {
              console.error('添加笔记失败:', error);
              alert('添加笔记失败');
            });
        },
        editNote(note) {
          this.isEditing = true;
          this.editingNoteId = note.id;
          this.newNote = { title: note.title, content: note.content };
        },
        updateNote() {
          axios.put(`http://localhost:3000/notes/${this.editingNoteId}`, this.newNote)
            .then(response => {
              this.cancelEdit();
              this.fetchNotes();
              alert(response.data.message);
            })
            .catch(error => {
              console.error('更新笔记失败:', error);
              alert('更新笔记失败');
            });
        },
        deleteNote(id) {
          if (confirm('确定要删除此笔记吗？')) {
            axios.delete(`http://localhost:3000/notes/${id}`)
              .then(response => {
                this.fetchNotes();
                alert(response.data.message);
              })
              .catch(error => {
                console.error('删除笔记失败:', error);
                alert('删除笔记失败');
              });
          }
        },
        cancelEdit() {
          this.isEditing = false;
          this.editingNoteId = null;
          this.newNote = { title: '', content: '' };
        }
      }
    });
  </script>
</body>
</html>