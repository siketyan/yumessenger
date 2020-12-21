import Dependencies._

enablePlugins(GatlingPlugin)

ThisBuild / scalaVersion     := "2.13.4"
ThisBuild / version          := "0.1.0-SNAPSHOT"
ThisBuild / organization     := "jp.co.yumemi"
ThisBuild / organizationName := "yumemi"

lazy val root = (project in file("."))
  .settings(
    name := "yumessenger",
    libraryDependencies ++= gatling
  )

// See https://www.scala-sbt.org/1.x/docs/Using-Sonatype.html for instructions on how to publish to Sonatype.
