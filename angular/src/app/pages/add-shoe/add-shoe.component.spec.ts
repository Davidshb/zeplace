import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AddShoeComponent } from './add-shoe.component';

describe('AddShoeComponent', () => {
  let component: AddShoeComponent;
  let fixture: ComponentFixture<AddShoeComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [AddShoeComponent]
    });
    fixture = TestBed.createComponent(AddShoeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
