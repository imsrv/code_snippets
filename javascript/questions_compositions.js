// React js
/*
This code creates an HTML webpage with a slider that allows users to adjust the values of different subjects (English, Hindi, Maths, and Science).
The slider ranges from 0 to 100 and displays the current value next to the subject name. As the user moves the slider, the value updates and affects the values of the other sliders to maintain a total value of 100 across all subjects.
The code uses JavaScript to update the slider values and ensure that they remain within the range of 0 to 100.
*/

import React, { useState } from 'react';

function Slider(props) {
  const [value, setValue] = useState(props.value);

  function handleChange(event) {
    const newValue = parseInt(event.target.value);
    const oldValue = value;
    const total = props.total - oldValue + newValue;

    if (total <= 100) {
      setValue(newValue);
      props.onChange(props.id, newValue);
    } else {
      setValue(100 - props.total + oldValue);
      props.onChange(props.id, 100 - props.total + oldValue);
    }
  }

  return (
    <div className="slider">
      <label className="form-label">{props.name} <span>{value}</span></label>
      <input type="range" className="form-range sub-range" min="0" max="100" value={value} onChange={handleChange} />
    </div>
  );
}

function App() {
  const [subjects, setSubjects] = useState([
    { id: "12", value: 100, name: "English" },
    { id: "13", value: 0, name: "Hindi" },
    { id: "14", value: 0, name: "Maths" },
    { id: "15", value: 0, name: "Science" }
  ]);

  function handleChange(id, value) {
    const newSubjects = subjects.map((subject) => {
      if (subject.id === id) {
        return { ...subject, value };
      } else {
        return subject;
      }
    });

    setSubjects(newSubjects);
  }

  return (
    <div className="container">
      <div className="mx-auto">
        <h2>Question Compositions</h2>
        {subjects.map((subject) => (
          <Slider
            key={subject.id}
            id={subject.id}
            name={subject.name}
            value={subject.value}
            total={subjects.reduce((total, subject) => total + subject.value, 0)}
            onChange={handleChange}
          />
        ))}
      </div>
    </div>
  );
}

export default App;
