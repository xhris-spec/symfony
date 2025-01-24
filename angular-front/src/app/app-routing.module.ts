import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './home/home.component';
import { ChampionComponent } from './champion/champion.component';

const routes: Routes = [
  { path: '', component: HomeComponent },
  { path: ':slug', component: ChampionComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
