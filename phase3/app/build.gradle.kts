import java.io.FileInputStream
import java.util.Properties

plugins {
    alias(libs.plugins.android.application)
    alias(libs.plugins.kotlin.android)
    alias(libs.plugins.kotlin.compose)
    id("org.jetbrains.kotlin.plugin.serialization") version "2.1.20"
}

android {
    namespace = "edu.uml.db2"
    compileSdk = 36

    defaultConfig {
        applicationId = "edu.uml.db2"
        minSdk = 30
        targetSdk = 36
        versionCode = 1
        versionName = "1.0"

        testInstrumentationRunner = "androidx.test.runner.AndroidJUnitRunner"
    }

    buildTypes {
        release {
            isMinifyEnabled = false
            proguardFiles(
                getDefaultProguardFile("proguard-android-optimize.txt"), "proguard-rules.pro"
            )
        }
    }
    compileOptions {
        sourceCompatibility = JavaVersion.VERSION_11
        targetCompatibility = JavaVersion.VERSION_11
    }
    kotlinOptions {
        jvmTarget = "11"
    }
    buildFeatures {
        compose = true
    }
}

dependencies {
    implementation(libs.androidx.core.ktx)
    implementation(libs.androidx.lifecycle.runtime.ktx)
    implementation(libs.androidx.activity.compose)
    implementation(platform(libs.androidx.compose.bom))
    implementation(libs.androidx.ui)
    implementation(libs.androidx.ui.graphics)
    implementation(libs.androidx.ui.tooling.preview)
    implementation(libs.androidx.material3)
    testImplementation(libs.junit)
    androidTestImplementation(libs.androidx.junit)
    androidTestImplementation(libs.androidx.espresso.core)
    androidTestImplementation(platform(libs.androidx.compose.bom))
    androidTestImplementation(libs.androidx.ui.test.junit4)
    debugImplementation(libs.androidx.ui.tooling)
    debugImplementation(libs.androidx.ui.test.manifest)
    implementation(libs.ktor.core)
    implementation(libs.ktor.okhttp)
    implementation(libs.ktor.negotiation)
    implementation(libs.ktor.json)
    implementation(libs.kotlinx.coroutines.android)
}

val backendPropertiesFile = rootProject.file("backend.properties")
val backendProperties = Properties().apply { load(FileInputStream(backendPropertiesFile)) }
val backendRootUrl = backendProperties["rootUrl"] as String

android {
    packaging {
        resources {
            excludes += "META-INF/INDEX.LIST"
        }
    }

    buildFeatures {
        buildConfig = true
    }

    buildTypes {
        debug {
            buildConfigField("String", "BACKEND_ROOT_URL", "\"$backendRootUrl\"")
            resValue("string", "BACKEND_ROOT_URL", backendRootUrl)
        }
        release {
            buildConfigField("String", "BACKEND_ROOT_URL", "\"$backendRootUrl\"")
            resValue("string", "BACKEND_ROOT_URL", backendRootUrl)
        }
    }
}